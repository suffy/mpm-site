

</div>
<div class="container-fluid">

<div class="row">
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
                                <div class="col-md-3">
                                    <label for="supp"><strong>Data Program</strong></label>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="supp">Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $namasupp ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Nomor Surat</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nomor_surat ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Account</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $account ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Area</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $area ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Brand</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $brand ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Item</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $item ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Mekanisme</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="" cols="30" rows="5" readonly><?= $mekanisme ?></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Expose</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $expose ?></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Periode</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $from.' s/d '.$to ?></label>
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
                                <div class="col-md-3">
                                    <label for="supp"><strong>Data MPI</strong></label>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="supp">Status DP</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" cols="30" rows="3" readonly><?= $nama_status_dp ?></textarea>
                                </div>
                            </div>                       
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Branch</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" cols="30" rows="3" readonly><?= $branch_name.'-'.$nama_comp.'-'.$site_code_db ?></textarea>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Nomor Ajuan</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nomor_ajuan ?></label>
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
                                        if (!empty($attach_1)) { ?>
                                           <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$attach_1 ?>" target="_blank" class ="btn btn-submit-black"><?= $attach_1 ?></a>
                                        <?php
                                        }else{
                                            echo '<label class="form-control" readonly>Tidak ada file</label>';
                                        }
                                    ?>  
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Attachment SKP</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if (!empty($attach_2)) { ?>
                                           <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$attach_2 ?>" target="_blank" class ="btn btn-submit-black"><?= mb_strimwidth($attach_2,0,20) ?></a>
                                        <?php
                                        }else{
                                            echo '<label class="form-control" readonly>Tidak ada file</label>';
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
                                <div class="col-md-3">
                                    <label for="supp"><strong>Data Verifikasi</strong></label>
                                </div>
                            </div>                  
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="supp">Status Verifikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nama_status_verifikasi ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Keterangan</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="" id="" cols="30" rows="3" class="form-control" readonly><?= $keterangan_verifikasi ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">Name</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $name_verifikasi ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Email</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $email_verifikasi ?></label>
                                </div>
                            </div>
                            
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Created_at</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $created_at_verifikasi ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Attachment PIC</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if (!empty($attach_1_verifikasi)) { ?>
                                           <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$attach_1_verifikasi ?>" target="_blank" class ="btn btn-submit-black"><?= mb_strimwidth($attach_1_verifikasi,0,20) ?></a>
                                        <?php
                                        }else{
                                            echo '<label class="form-control" readonly>Tidak ada file</label>';
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
                                <div class="col-md-3">
                                    <label for="supp"><strong>Data Hardcopy</strong></label>
                                </div>
                            </div>                 
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="supp">Status Hardcopy DP</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $nama_status_hardcopy_dp ?></label>
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
                                    <label for="supp">Tanggal Kirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_kirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">File Resi</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if (!empty($file_hardcopy)) { ?>
                                           <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$file_hardcopy ?>" target="_blank" class ="btn btn-submit"><?= $file_hardcopy ?></a>
                                        <?php
                                        }else{
                                            echo '<label class="form-control" readonly>Tidak ada file</label>';
                                        }
                                    ?>                                    
                                </div>
                            </div>

                           <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="supp">Tanggal Terima</label>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-control" readonly><?= $tanggal_terima_hardcopy ?></label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>