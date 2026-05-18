</div>

<div class="container-fluid">

    <div class="row mt-2">
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
    
    <?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-6">

            <div class="col-md-12">
                <?= $title ?>
            </div>

            <div class="col-md-12 mt-4">     
                <label for="aktivitas" >Aktivitas</label>
                <div class="col-md-10 d-flex flex-row">
                    <input class="form-control form-control-md" id="aktivitas" type="text" name="aktivitas" required>
                </div>
            </div>

            <div class="col-md-12 mt-2">     
                <label for="tanggal_aktivitas" >Tanggal Aktivitas</label>
                <div class="col-md-10 d-flex flex-row">
                    <input class="form-control" type="datetime-local" name="tanggal_aktivitas" id="tanggal_aktivitas" required />
                </div>
            </div>

            <div class="col-md-12 mt-2">     
                <label for="detail_aktivitas" >Detail Aktivitas</label>
                <div class="col-md-10 d-flex flex-row">
                    <textarea name="detail_aktivitas" id="detail_aktivitas" cols="30" rows="5" class="form-control" required></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-4">     
                <label for="biaya" >Biaya yang dibutuhkan (Jika tidak ada, isi dengan angka 0)</label>
                <div class="col-md-10 d-flex flex-row">
                    <input class="form-control form-control-md" id="biaya" type="number" name="biaya" placeholder="Contoh : 100000" required>
                </div>
            </div>

            <div class="col-md-12 mt-4">     
                <label for="biaya" >Apakah akan di claim ?</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_claim" id="status_claim1" value="1" required>
                        <label class="form-check-label" for="status_claim1">
                            Ya, perlu di claim
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_claim" id="status_claim2" value="0" required>
                        <label class="form-check-label" for="status_claim2">
                            Tidak perlu
                        </label>
                    </div>

            </div>

            <div class="col-md-12 mt-2">     
                <label for="keterangan" >Input Akomodasi dan Transportasi</label>
                <div class="col-md-10 d-flex flex-row">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="2" class="form-control" placeholder="Untuk akomodasi dan transportasi bisa diinput disini" <?= $this->session->userdata('supp') == '001' ? 'required' : '' ?>></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-2 mb-5">     
                <label for="keterangan" ></label>
                <div class="col-md-10">
                    <input class="form-control form-control-md" id="aktivitas" type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>" required>
                    <input class="form-control form-control-md" id="signature_pengajuan" type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>" required>


                    <?php 
                        if ($status == 6) { ?>
                            <?php echo form_open_multipart($url_verifikasi); ?>
                                <input type="submit" value="Submit Aktivitas" class="btn btn-submit-black">
                            </form>
                        <?php 
                        }else{ ?>
                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                        <?php
                        }
                    ?>

                    <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-submit-black">back to pengajuan RPD</a>
                
                </div>
            </div>
        </div>

        
        

        <div class="col-md-6 border rounded-10 shadow p-3">

            <div class="col-md-12 mt-4 text-center">
                <h4>Form Perjalanan Dinas</h4>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >No RPD</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $no_rpd ?>">
                </div>
            </div>
            
            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Pelaksana</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $pelaksana ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Maksud Perjalanan Dinas</label>
                </div>
                <div class="col-md-8">
                    <textarea name="" id="" cols="30" rows="5" class="form-control"><?= $maksud_perjalanan_dinas ?></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Berangkat</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $berangkat ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Tiba</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $tiba ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Status</label>
                </div>
                <div class="col-md-8">
                    <?= $nama_status ?>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Total Biaya</label> 
                </div>
                <div class="col-md-8">
                    <font style="color:var(--bs-dark-text-emphasis)" size="5px">Rp. <?= number_format($total_biaya) ?></font>
                </div>
            </div>

            <hr>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Jumlah Verifikasi</label>
                </div>
                <div class="col-md-8">
                    <?= $jumlah_verifikasi ?>
                </div>
            </div>       

            <?php 

            if ($jumlah_verifikasi == 1) { ?>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Verifikasi 1</label>
                </div>
                <div class="col-md-8">
                    <?php 
                        if ($verifikasi1_at) {
                            echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1. ' - '.$verifikasi1_keterangan;
                        }
                    ?>
                </div>
            </div>

            <div class="col-md-12 mt-5">     
                <div class="col-md-12 text-center">
                    <h5>PIC Verifikasi</h5>
                </div>
            </div>

            <div class="col-md-12 mt-5 d-flex justify-content-center">     
                <div class="col-md-6 text-center">

                    <?php 
                        if ($this->session->userdata('id') === $userid_verifikasi1 && !$verifikasi1_ttd) { ?>
                            <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-delete btn-sm btn-rounded" target="_blank">verifikasi click disini</a>
                        <?php
                        }elseif ($verifikasi1_ttd){
                            $file = './assets/uploads/signature/'.$verifikasi1_ttd;
                            if (file_exists($file)) { ?>
                                <!-- <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="150px"> -->
                                <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi1_ttd ?>" alt="ttd1" width="100px">
                            <?php
                            }?>
                        <?php
                        }
                        ?>
                </div>
            </div>

            <div class="col-md-12 mt-5 d-flex justify-content-center">     
                <div class="col-md-6 text-center">
                    <p>
                        <?= $username_verifikasi1 ?>
                        (verifikasi 1)
                    </p>
                </div>
            </div>        
                        
            <?php
            }elseif($jumlah_verifikasi == 2){ ?>


            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Verifikasi 1</label>
                </div>
                <div class="col-md-8">
                    <?php 
                        if ($verifikasi1_at) {
                            echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1. ' - '.$verifikasi1_keterangan;
                        }
                    ?>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" >Verifikasi 2</label>
                </div>
                <div class="col-md-8">
                <?php 
                        if ($verifikasi2_at) {
                            echo $verifikasi2_name.' at '.$verifikasi2_at. ' by '.$username_verifikasi2. ' - '.$verifikasi2_keterangan;
                        }
                    ?>
                </div>
            </div>

            <div class="col-md-12 mt-5">     
                <div class="col-md-12 text-center">
                    <h5>PIC Verifikasi</h5>
                </div>
            </div>

            <div class="col-md-12 mt-5 d-flex justify-content-center">     
                <div class="col-md-6 text-center">

                    <?php 
                        if ($this->session->userdata('id') === $userid_verifikasi1 && !$verifikasi1_ttd) { ?>
                            <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-submit" target="_blank">verifikasi click disini</a>
                        <?php
                        }elseif ($verifikasi1_ttd){
                            $file = './assets/uploads/signature/'.$verifikasi1_ttd;
                            if (file_exists($file)) { ?>
                                <!-- <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="100px"> -->
                                <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi1_ttd ?>" alt="ttd1" width="100px">
                            <?php
                            }?>
                        <?php
                        }
                    ?>
                </div>

                <div class="col-md-6 text-center">

                    <?php 
                        if ($this->session->userdata('id') === $userid_verifikasi2 && !$verifikasi2_ttd) {
                            if ($status == '2') { ?>
                                <a href="<?= base_url().'management_rpd/verifikasi2/'.$signature_pengajuan ?>" class="btn btn-submit" target="_blank">verifikasi click disini</a>
                            <?php
                            }
                        }elseif ($verifikasi2_ttd){
                            $file = './assets/uploads/signature/'.$verifikasi2_ttd;
                            if (file_exists($file)) { ?>
                                <!-- <img src="<?= $file ?>" alt="<?= $verifikasi2_ttd ?>" width="150px"> -->
                                <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi2_ttd ?>" alt="ttd1" width="100px">
                            <?php
                            }?>
                        <?php
                        }
                    ?>
                </div>
            </div>

            <div class="col-md-12 mt-5 d-flex justify-content-center">     
                <div class="col-md-6 text-center">
                    <p>
                        <?= $username_verifikasi1 ?>
                        (verifikasi 1)
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <p>
                        <?= $username_verifikasi2 ?>
                        (verifikasi 2)
                    </p>
                </div>
            </div> 



            <?php
            }

            ?>


        </div>

    </div>

</form>

<hr>

<div class="container-fluid">

    <div class="row mt-5">
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

    <div class="row mt-5">
        <div class="col-md-12 az-content-label text-center">
                Detail Aktivitas
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="workspace" style="width: 100%;">
                <thead>
                    <tr>
                        <th class = "text-center" >Tanggal</th>
                        <th class = "text-center" >Aktivitas</th>
                        <th class = "text-center" >Detail</th>
                        <th class = "text-center" >Biaya</th>
                        <th class = "text-center" >Claim</th>
                        <th class = "text-center" >Keterangan</th>
                        <th class = "text-center" style="width: 50px;">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_aktivitas->result() as $a) : ?>
                    <tr>
                        <td><?= $a->tanggal_aktivitas; ?></td>
                        <td><?= $a->aktivitas; ?></td>
                        <td><?= $a->detail_aktivitas; ?></td>
                        <td><?= number_format($a->biaya); ?></td>
                        <td>
                            <?= ($a->status_claim == 1) ? 'Ya' : 'No' ?>
                        </td>
                        <td><?= $a->keterangan; ?></td>
                        <td align="center">
                            <?php 
                                if ($status == 6) { ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas_delete_soft/'.$a->signature.'/'.$signature_pengajuan ?>" class="btn btn-delete btn-sm" onclick="return confirm('Yakin menghapus row ini ?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a>
                                <?php 
                                }else{ ?>
                                    <button type="submit" class="btn btn-dark" disabled>X</button>
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

<hr>

<div class="row mb-5">
    <div class="col-md-12 text-center">
        <p><strong>Isi aktivitas. Jika sudah ok, klik Button "Meminta Persetujuan RPD ke Atasan" :</strong></p>
    </div>

    <div class="col-md-12 d-flex justify-content-center">
        
        <div class="form-inline">
            <div class="col-sm-9">

                <?php 
                    if ($status == 6) { ?>
                        <?php echo form_open_multipart($url_verifikasi); ?>
                            <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
                            <input type="submit" value="Meminta Persetujuan RPD ke Atasan" class="btn btn-submit">
                        </form>
                    <?php 
                    }else{ ?>
                        <button type="submit" class="btn btn-pending" disabled>permintaan sudah diajukan</button>
                    <?php
                    }
                ?>               
            </div>
        </div>
    </div>
</div>

</div>

<script>
      $(document).ready(function () {
        $("#workspace").DataTable({
            "scrollX": true,
            "pageLength": 10,
            "ordering": true,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
      });
</script>