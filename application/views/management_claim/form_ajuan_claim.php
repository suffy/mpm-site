<?php
foreach ($site_code_form->result() as $a) 
{
    $site_code      = $a->site_code;
    $nama_comp      = $a->nama_comp;
    $branch_name    = $a->branch_name;
    $site[$a->site_code] = $a->branch_name.' - '.$a->site_code;
}
?>

</div>

<div class="container-fluid">
    
<?php echo form_open_multipart($url); ?>

<div class="row mt-1">
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


<!-- <div class="row mt-2">
    <div class="col-lg-3">
        <label for="tanggal_terima_barang">Status Validasi</label>
    </div>
    <div class="col-lg-5 d-flex flex-row">
        <?php 
            if ($params_status_validasi == 1) { ?>
                <label class="form-control" for="status_validasi" readonly>Website akan memvalidasi data anda</label>
                <?php
            }else{ ?>
                <label class="form-control" for="status_validasi" readonly>Tidak ada validasi data</label>
            <?php
            }
        ?>
    </div>
</div> -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Informasi Program </h5>

        <div class="row mt-4">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Status</label>
            </div>
            <div class="col-lg-5">
                <label class="form-control" readonly>
                    <?php
                        if($nama_status){
                            echo $nama_status;
                        }else{ ?>
                            belum ada. Silahkan ajukan claim terlebih dahulu
                        <?php
                        }
                    ?>
                </label>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Nomor Surat</label>
            </div>
            <div class="col-lg-5">
                <label class="form-control" readonly><?= $nomor_surat ?></label>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Nama Program | Kategori</label>
            </div>
            <div class="col-lg-5">
                <textarea name="" id="" cols="30" rows="3" class="form-control" readonly><?= $nama_program.' | '.$kategori ?></textarea>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Branch</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <input type="text" class="form-control" value="<?= $branch_name.' - '.$nama_comp.' - '.$site_code ?>" readonly>
                <input type="hidden" class="form-control" name="branch_name" value="<?= $branch_name ?>" readonly>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">PIC MPM</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <label for="username" class="form-control" readonly><?= $username ?></label>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">First PIC</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <label for="username" class="form-control" readonly><?= $pic ?></label>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="status_validasi">Status Validasi</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <label for="status_validasi" class="form-control" readonly><?= $nama_status_validasi.' - '.$keterangan ?></label>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="status_validasi">Deadline Pengajuan Claim</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <label for="status_validasi" class="form-control" readonly><?= $duedate ?></label>
            </div>
        </div>
        
        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="status_validasi">Template</label>
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <?php 
                    if ($filename) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/template/'.$filename ?>" class="btn btn-submit-orange" target="_blank"><?= $nama_template ?></a>
                    <?php
                    }else{ ?>
                        <label for="status_validasi" class="form-control" readonly>-</label>
                    <?php
                    }
                ?>
                
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <input type="hidden" class="form-control" name="nama_comp" value="<?= $nama_comp ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <input type="hidden" class="form-control" name="site_code" value="<?= $site_code ?>" readonly>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Isi data anda disini </h5>

        <div class="row mt-4">
            <div class="col-lg-3">
                <label for="nomor_ajuan">Nomor Ajuan</label>
            </div>
            <div class="col-lg-5">
                <input class="form-control form-control-md" id="nomor_ajuan" type="text" name="nomor_ajuan" value="<?= $nomor_ajuan ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Nama PIC DP</label>
            </div>
            <div class="col-lg-5">
                <input class="form-control form-control-md" id="nama_pengirim" type="text" name="nama_pengirim" placeholder=" ... isi nama anda" required>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Email PIC DP</label>
            </div>
            <div class="col-lg-5">
                <input class="form-control form-control-md" id="email_pengirim" type="text" name="email_pengirim" placeholder=" ... isi email anda" required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-3">
                <label for="ajuan_excel">Upload Excel Claim (.xlsx)</label>
            </div>
            <div class="col-lg-5">
                <input class="form-control form-control-md" id="ajuan_excel" type="file" name="ajuan_excel" required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-3">
                <label for="tanggal_terima_barang">Upload Dokumen Pendukung yang sudah di ZIP (.zip)</label>
            </div>
            <div class="col-lg-5">
                <input class="form-control form-control-md" id="ajuan_zip" type="file" name="ajuan_zip">
                <input type="hidden" name="signature_program" value="<?= $signature_program ?>" required>
                <input type="hidden" name="supp" value="<?= $supp ?>" required>
            </div>
        </div>

        <input type="hidden" name="status_data_final" value="1">

        <div class="row mt-4 mb-5">
            <div class="col-lg-3">
                
            </div>
            <div class="col-md-5">
                <?php 
                    if ($status == 1) { 
                        ?>
                        <?php
                            if ($selisih_duedate >= 0 || $mpm_at != null) { ?>
                                <button type="submit" class="btn btn-submit-red" id="btnKirim" style="height: 45px;" onclick="return button()">Submit Pengajuan Claim</button>
                            <?php
                            }else{ ?>         
                                <button type="submit" class="btn btn-submit-black" disabled>Anda sudah melewati deadline</button>
                            <?php
                            }
                            
                        ?>
                        <?php 
                    }else{ ?>
                        <button type="submit" class="btn btn-submit-black" disabled>data anda sudah masuk</button>              
                    <?php }
                ?>
                <button class="btn btn-info" id="btnLoading" type="button" disabled>
                ... Sedang mengupload data. Mohon menunggu ...
                </button>
                <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" id="btnBack" style="width: 100px;">Back</a>
            </div>
        </div>




    </div>
</div>

<?php 
    if($params_status_validasi == 1){ ?> <!-- jika harus melalui proses validasi -->

        <?php 
            if ($upload_template_program) { ?>

                <div class="row mt-4">
                    <div class="col-md-11">
                        <marquee behavior="scroll" direction="" scrolldelay="1"><p><strong>Silahkan kirim ajuan claim anda dengan menggunakan template di bawah ini : </strong></p></marquee>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_template_program ?>" class="btn btn-submit-red" target="_blank"><?= $upload_template_program ?></a> <strong> <-- download template disamping</strong>
                    </div>
                </div>

            <?php    
            }
        ?>

        <?php
            if ($kategori == 'bonus_barang') { ?>
                <div class="row mt-5">
                    <div class="col-lg-11">
                        <marquee onmouseover="this.stop();" onmouseout="this.start();" behavior="scroll" direction="" scrolldelay="1"><p><strong>Khusus kategori bonus barang, DP diharuskan mengikuti standar template di bawah ini :</strong></p></marquee>                
                        <a href="<?= base_url().'management_claim/export_template_bonus_barang/'.$signature_program ?>" class="btn btn-submit" id="download">download template bonus barang.xlsx</a>
                        <a href="<?= base_url().'management_claim/export_master_site/'.$site_code ?>" class="btn btn-submit" id="download">download master site MPM</a>
                        <a href="<?= base_url().'management_claim/export_master_class' ?>" class="btn btn-submit" id="download">download master class MPM</a>
                    </div>
                </div>
            <?php    
            }
        ?>

        <?php
            if ($kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon') { ?>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <marquee onmouseover="this.stop();" onmouseout="this.start();" behavior="scroll" direction="" scrolldelay="1"><p><strong>Khusus kategori diskon_herbal, diskon_candy, diskon. Maka DP diharuskan mengikuti standar template di bawah ini :</strong></p></marquee>            
                        <a href="<?= base_url().'management_claim/export_template_diskon/'.$signature_program ?>" class="btn btn-submit mt-3" id="download">download template diskon.xlsx</a>
                        <a href="<?= base_url().'management_claim/export_master_site/'.$site_code ?>" class="btn btn-submit mt-3" id="download">download master site MPM</a>
                        <a href="<?= base_url().'management_claim/export_master_class' ?>" class="btn btn-submit mt-3" id="download">download master class MPM</a>
                    </div>
                </div>
            <?php    
            }
        ?>

    <?php
    } else{ ?>
        <?php 
            if ($upload_template_program) { ?>

                <div class="row mt-4">
                    <div class="col-md-7">
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_template_program ?>" class="btn btn-submit-cream" target="_blank">klik disini untuk mendownload template</a> <strong> <-- selalu gunakan template sesuai instruksi</strong>
                    </div>
                </div>

            <?php    
            }
        ?>

    <?php
    }
?>

</form>


</div>
</div>

<br><br>

<script>
    function button()
    {
        var nama_pengirim   = document.getElementById('nama_pengirim').value;
        var email_pengirim  = document.getElementById('email_pengirim').value;
        var ajuan_excel     = document.getElementById('ajuan_excel').value;
        if (nama_pengirim) {
            if (email_pengirim) {
                if (ajuan_excel) {
                    $("#btnKirim").hide();
                    $("#btnBack").hide();
                    $("#btnLoading").show();
                }   
            }
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>