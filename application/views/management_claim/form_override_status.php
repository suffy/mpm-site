</div>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-">
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

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Nomor Ajuan</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $no_pengajuan ?>" readonly>
                </div>
            </div>
        </div> 
    </div>
    
    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Principal</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $namasupp ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Tanggal Pengajuan</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $tanggal_pengajuan ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">DP</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $branch_name.' - '.$nama_comp. ' - '.$site_code ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Status</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $nama_status ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">File</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file ?>" target="_blank" class="btn btn-submit-black"><?= $file ?></a>
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

    <hr>

     <div class="row mt-4">
        <div class="col-md-12">
            <strong>Verifikasi Principal Area</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Principal Area</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $principal_area_name ?>" readonly>
                </div>
            </div>
        </div> 
    </div>     

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Principal Area At</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $principal_area_at ?>" readonly>
                </div>
            </div>
        </div> 
    </div>    

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">File Principal Area</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file_principal_area) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file_principal_area ?>" target="_blank" class="btn btn-submit-black"><?= $file_principal_area ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Catatan Principal Area</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $catatan_principal_area ?>" readonly>
                </div>
            </div>
        </div> 
    </div>  

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Verifikasi MPM</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Verifikasi MPM</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $verifikasi_mpm_name ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Verifikasi MPM At</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $verifikasi_at ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Verifikasi Principal HO</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Principal HO</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $principal_ho_name ?>" readonly>
                </div>
            </div>
        </div> 
    </div>   

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Principal HO At</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $principal_ho_at ?>" readonly>
                </div>
            </div>
        </div> 
    </div>  

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">File Principal HO</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file_principal_ho) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file_principal_ho ?>" target="_blank" class="btn btn-submit-black"><?= $file_principal_ho ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Catatan Principal HO</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $catatan_principal_ho ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Pengiriman Barang</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Tanggal Kirim Barang</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $tanggal_kirim_barang ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Ekspedisi</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $nama_ekspedisi ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Estimasi Tanggal Tiba</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $est_tanggal_tiba ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">File Resi</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file_pengiriman) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file_pengiriman ?>" target="_blank" class="btn btn-submit-black"><?= $file_pengiriman ?></a>
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

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Pemusnahan</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Tanggal Pemusnahan</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $tanggal_pemusnahan ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 
    
    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">PIC Pemusnahan</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $nama_pemusnahan ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Berita Acara</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file_pemusnahan) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file_pemusnahan ?>" target="_blank" class="btn btn-submit-black"><?= $file_pemusnahan ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Foto 1</label>
                <div class="col-lg-8">
                    <?php 
                        if ($foto_pemusnahan_1) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$foto_pemusnahan_1 ?>" target="_blank" class="btn btn-submit-black"><?= $foto_pemusnahan_1 ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Foto 2</label>
                <div class="col-lg-8">
                    <?php 
                        if ($foto_pemusnahan_2) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$foto_pemusnahan_2 ?>" target="_blank" class="btn btn-submit-black"><?= $foto_pemusnahan_2 ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Video</label>
                <div class="col-lg-8">
                    <?php 
                        if ($video) { ?>
                            <video width="320" height="240" controls>
                                <source src="<?= base_url().'assets/file/retur/'.$video ?>" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                            <a href="<?= base_url().'assets/file/retur/'.$video ?>" class="btn btn-secondary btn-sm rounded" target="_blank" download>download</a>
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

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Terima Barang</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Tanggal Terima Barang</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $tanggal_terima_barang ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Nama Penerima</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $nama_penerima ?>" readonly>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">No Terima Barang</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $no_terima_barang ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">File Terima Barang</label>
                <div class="col-lg-8">
                    <?php 
                        if ($file_terima_barang) { ?>
                            <a href="<?= base_url().'assets/file/retur/'.$file_terima_barang ?>" target="_blank" class="btn btn-submit-black"><?= $file_terima_barang ?></a>
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

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Terima Barang At</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $terima_barang_at ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <strong>Override Status</strong>
        </div>
    </div>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Last Updated</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $last_updated ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <div class="row"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 form-label">Last Updated By</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?= $last_updated_name ?>" readonly>
                </div>
            </div>
        </div> 
    </div>

    <?= form_open($url); ?>

    <div class="row mt-4"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 col-form-label"><strong>Override Status to</strong></label>
                <div class="col-lg-8">
                    <select name="status" class="form-control">
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>1. PENDING DP</option>
                        <option value="2" <?= $status == 2 ? 'selected' : '' ?>>2. PENDING MPM</option>
                        <option value="3" <?= $status == 3 ? 'selected' : '' ?>>3. PENDING PRINCIPAL AREA</option>
                        <option value="4" <?= $status == 4 ? 'selected' : '' ?>>4. PENDING PRINCIPAL HO</option>
                        <option value="5" <?= $status == 5 ? 'selected' : '' ?>>5. PENDING KIRIM BARANG</option>
                        <option value="6" <?= $status == 6 ? 'selected' : '' ?>>6. PENDING TERIMA BARANG</option>
                        <option value="7" <?= $status == 7 ? 'selected' : '' ?>>7. PENDING PEMUSNAHAN</option>
                        <option value="8" <?= $status == 8 ? 'selected' : '' ?>>8. BARANG DITERIMA</option>
                        <option value="9" <?= $status == 9 ? 'selected' : '' ?>>9. PEMUSNAHAN OLEH DP</option>
                        <option value="10" <?= $status == 10 ? 'selected' : '' ?>>10. REJECT PRINCIPAL HO</option>
                        <option value="11" <?= $status == 11 ? 'selected' : '' ?>>11. RETUR SAMPLE</option>
                        <option value="13" <?= $status == 13 ? 'selected' : '' ?>>13. REJECT</option>
                    </select>
                </div>
            </div>
        </div> 
    </div>
    

    <div class="row mt-2"> 
        <div class="col-md-6">      
            <div class="form-group row">
                <label for="nomor_ajuan" class="col-lg-4 col-form-label"></label>
                <div class="col-lg-8">
                    <input type="hidden" name="signature" value="<?= $signature ?>">
                    <input type="submit" class ="btn btn-submit-black" value="Submit">
                    <input type="reset" class ="btn btn-submit-grey" value="Reset">
                </div>
            </div>
        </div> 
    </div>

    <?= form_close(); ?>


    


<script>

    

    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
            // table
            // .columns(3)
            // .search(this.value)
            // .draw()
        });

        var table = new DataTable('#example');
 
        // #column3_search is a <input type="text"> element
        $('#column3_search').on('keyup', function () {
            table
                .columns(4)
                .search(this.value)
                .draw();
        });


    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
