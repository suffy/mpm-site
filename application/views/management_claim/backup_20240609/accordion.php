</div>

<div class="container-fluid">

<div class="row mb-4">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <p>
            <button class="btn btn-submit-black" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Lihat Detail Program</button>
        </p>
        <div class="row">
                    
            <div class="col-md-6">
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                        <div class="container">    
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label"><strong>DATA PROGRAM</strong></label>
                                </div>
                            </div>                        
                        
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Status</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= ($nama_status) ? $nama_status : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Status Internal</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= ($nama_status_internal) ? $nama_status_internal : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">No Ajuan Claim</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= ($nomor_ajuan) ? $nomor_ajuan : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= $kategori ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= $namasupp ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Periode</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= $from.' s/d '.$to ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nama Program</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class = "form-control" id="" cols="30" rows="3" readonly><?= $nama_program ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nomor Surat</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" class="form-control" readonly><?= $nomor_surat ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Syarat Ketentuan</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="" id="" cols="30" rows="5"  class="form-control" readonly><?= $syarat ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Attachment</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if ($upload_jpg) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_jpg ?>" class='btn btn-submit-cream'>
                                            <?= $upload_jpg ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                        <?php
                                        }

                                        if ($upload_pdf) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_pdf ?>" class='btn btn-submit-cream'>
                                            <?= $upload_pdf ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                        <?php
                                        }
                                    ?>    
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

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label"><strong>DATA CLAIM DP</strong></label>
                                </div>
                            </div>  
                    
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">Branch</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $branch_name.' - '.$nama_comp.' - '.$site_code ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Nama Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nama_pengirim ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Email Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $email_pengirim ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_claim">Tanggal Claim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_claim ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_claim">Created_at</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $created_at ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Attachment Data</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if ($ajuan_excel) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_excel ?>" class='btn btn-submit-cream'>
                                            <?= $ajuan_excel ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                        <?php
                                        }

                                        if ($ajuan_zip) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_zip ?>" class='btn btn-submit-cream'>
                                            <?= $ajuan_zip ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
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

        <div class="row">

            <div class="col-md-6">
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                        <div class="container"> 
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label"><strong>DATA VERIFIKASI</strong></label>
                                </div>
                            </div>  
                            
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">Verifikasi By</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $verifikasi_username ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Keterangan</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="" id="" cols="30" rows="5"  class="form-control" readonly><?= $verifikasi_keterangan ?></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">File</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if ($verifikasi_file) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$verifikasi_file ?>" class='btn btn-submit-cream'>
                                            <?= $verifikasi_file ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                        <?php
                                        }
                                    ?>   
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Verifikasi At</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $verifikasi_created_at ?></label>
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
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label"><strong>DATA HARDCOPY DP</strong></label>
                                </div>
                            </div> 

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">Status</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nama_status_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Nomor Resi</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nomor_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Tanggal Kirim DP</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_kirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Nama Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nama_pengirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Email Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $email_pengirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Updated At</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $update_kirim_hardcopy_at ?></label>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label"><strong>DATA HARDCOPY di MPM</strong></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Tanggal Terima oleh MPM</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_terima_hardcopy ?></label>
                                </div>
                            </div>                
                            
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Staff Penerima</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $terima_hardcopy_nama ?></label>
                                </div>
                            </div>             
                            
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Tanggal Serah Terima ke Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_tanda_terima_hardcopy_ke_principal ?></label>
                                </div>
                            </div> 

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Staff Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanda_terima_hardcopy_ke_principal_nama ?></label>
                                </div>
                            </div> 

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Tanda Terima MPM ke Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if ($file_tanda_terima_hardcopy_ke_principal) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$file_tanda_terima_hardcopy_ke_principal ?>" class='btn btn-submit-cream'>
                                            <?= $file_tanda_terima_hardcopy_ke_principal ?></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file</i></label>
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
        
    </div>
</div>