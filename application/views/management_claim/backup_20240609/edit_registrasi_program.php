</div>

<div class="container-fluid">
    
<?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

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

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal</label> 
        </div>
        <div class="col-md-4">

            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001" <?php if($supp == '001') { echo 'selected'; } ?> > Deltomed </option>
                <option value="002" <?php if($supp == '002') { echo 'selected'; } ?> > Marguna </option>
                <option value="005" <?php if($supp == '005') { echo 'selected'; } ?> > Ultra Sakti </option>
                <option value="012" <?php if($supp == '012') { echo 'selected'; } ?> > Intrafood </option>
                <option value="013" <?php if($supp == '013') { echo 'selected'; } ?> > Strive </option>
                <option value="015" <?php if($supp == '015') { echo 'selected'; } ?> > MDJ </option>
                <option value="025" <?php if($supp == '025') { echo 'selected'; } ?> > PT. GOOD PHARMA DERMATOLOGY </option>
                <option value="026" <?php if($supp == '026') { echo 'selected'; } ?> > PT. GUNUNG SUBUR SEJAHTERA </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode Program</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" value="<?= $from ?>" required>
            <input class="form-control form-control-md" id="from" type="date" name="to" value="<?= $to ?>" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="kategori" class="form-label">Kategori</label>
        </div>
        <div class="col-md-4">
            <select id="kategori" name="kategori" class="form-control" required>
                <option value=""> -- pilih kategori -- </option>
                <option value="loyalty" <?php if($kategori == 'loyalty') { echo 'selected'; } ?> > Loyalty </option>
                <option value="bonus_barang" <?php if($kategori == 'bonus_barang') { echo 'selected'; } ?> > Bonus Barang</option>
                <option value="diskon_herbal" <?php if($kategori == 'diskon_herbal') { echo 'selected'; } ?> > Diskon Herbal</option>
                <option value="diskon_candy" <?php if($kategori == 'diskon_candy') { echo 'selected'; } ?> > Diskon Candy</option>
                <option value="diskon" <?php if($kategori == 'diskon') { echo 'selected'; } ?> > Diskon</option>
                <option value="insentif" <?php if($kategori == 'insentif') { echo 'selected'; } ?> > Insentif </option>
                <option value="listing_fee" <?php if($kategori == 'listing_fee') { echo 'selected'; } ?> > Listing Fee </option>
                <option value="rafaksi" <?php if($kategori == 'rafaksi') { echo 'selected'; } ?> > Rafaksi </option>
                <option value="program MT" <?php if($kategori == 'program MT') { echo 'selected'; } ?> > Program MT </option>
                <option value="sewa_display" <?php if($kategori == 'sewa_display') { echo 'selected'; } ?> > Sewa Display </option>
                <option value="salesman_herbana" <?php if($kategori == 'salesman_herbana') { echo 'selected'; } ?> > Salesman Herbana </option>
                <option value="delto_corner" <?php if($kategori == 'delto_corner') { echo 'selected'; } ?> > Delto Corner </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="status_template" class="form-label">Lampirkan Template tambahan ? </label>
        </div>
        <div class="col-md-4">
            <select id="status_template" name="status_template" class="form-control" required>
                <option value=""> -- pilih status template -- </option>
                <option value=1> Ya </option>
                <option value=0> Tidak</option>
                <option value=9 selected> Gunakan data sebelumnya</option>
            </select>
        </div>
    </div>

    <input type="hidden" name="template_old" value="<?= $upload_template_program ?>">

    <div class="row mt-3" hidden id="mydiv">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Template Program</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" type="file" id="template_program" name="template_program">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Nomor Surat Program</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nomor_surat" type="text" name="nomor_surat" value="<?= $nomor_surat ?>" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program" class="form-label">Nama Program</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nama_program" type="text" name="nama_program" value="<?= $nama_program ?>" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="syarat" class="form-label">Syarat Ketentuan</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <textarea class="form-control" id="syarat" name="syarat" cols="5" rows="5" required> <?= $syarat ?></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="duedate" class="form-label">Deadline Ajuan Claim</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="duedate" type="date" name="duedate" value="<?= $duedate ?>" required>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen (.jpg)</label>
        </div>
        <div class="col-md-4">
            <?php 
                if ($upload_jpg) { ?>
                    <a href="<?= base_url() ?>assets/uploads/management_claim/<?= $upload_jpg ?>" class="btn btn-outline-dark btn-sm" target="_blank" style ="width: 100%;">
                    <input class="form-control form-control-md" type="text" value="<?= $upload_jpg ?>">
                </a>  
                <?php
                }
            ?>
            <br><br>
            <input class="form-control form-control-md" type="hidden" name="upload_jpg_old" value="<?= $upload_jpg ?>">
            <input class="form-control form-control-md" id="upload_jpg" type="file" name="upload_jpg">
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="upload_pdf" class="form-label">Upload Dokumen (.pdf) (*)</label>
        </div>
        <div class="col-md-4">

        <?php 
            if ($upload_pdf) { ?>
                <a href="<?= base_url() ?>assets/uploads/management_claim/<?= $upload_pdf ?>" class="btn btn-outline-dark btn-sm" target="_blank" style ="width: 100%;"><input class="form-control form-control-md" type="text" value="<?= $upload_pdf ?>"></a> 
            <?php
            }
        ?>
        <br><br>
            <input class="form-control form-control-md" type="hidden" name="upload_pdf_old" value="<?= $upload_pdf ?>">
            <input class="form-control form-control-md" id="upload_pdf" type="file" name="upload_pdf">
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="first_hand" class="form-label">First Hand</label>
        </div>
        <div class="col-md-5">
            <select id="first_hand" name="first_hand" class="form-control" required>
                <option value=""> -- Pilih First Hand -- </option>
                <option value='mpm' <?= $first_hand == 'mpm' ? 'selected' : '' ?>> PIC MPM</option>
                <option value='principal' <?= $first_hand == 'principal' ? 'selected' : '' ?>> PIC Principal</option>
            </select>
        </div>
    </div>

    <input type="hidden" name="signature" value="<?= $signature ?>">

    <div class="row mt-5">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-7">
            <button type="submit" class="btn btn-submit-black">Update Registrasi Program</button>
            <a href="<?= base_url('management_claim/registrasi_program') ?>" class="btn btn-submit-black" id="btnBack" style="padding: 10px 10px 10px 10px; width: 80px;">back</a>
        </div>
    </div>
</form>

    <div class="card-block mt-2 mb-5">
        <div class="row">
            <div class="col-md-12">
                <hr class="batas">
            </div>
        
            <div class="col-md-12 mt-4">

                <table id="tabel-registrasi">
                    <thead>
                        <tr>
                            <th class="text-center">Principal</th>
                            <th class="text-center">NomorSurat</th>
                            <th class="text-center">NamaProgram</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">Syarat</th>
                            <th class="text-center">Dokumen</th>
                            <th class="text-center">CreatedBy</th>
                            <th class="text-center">Deadline</th>
                            <th class="text-center">First Hand</th>
                            <th class="text-center">TemplateTambahan</th>
                            <th class="text-center">Del</th>                            
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                        foreach ($get_registrasi_program->result() as $a) : ?>
                        <tr>
                            <td><?= $a->namasupp; ?></td>
                            <td><?= $a->nomor_surat; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td><?= $a->kategori; ?></td>
                            <td><?= $a->from.' sd '.$a->to; ?></td>
                            <td><?= $a->syarat; ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php 
                                        if ($a->upload_jpg) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_jpg ?>" class="btn btn-submit-black" target="_blank" style="width: 50px">jpg</a>
                                        <?php
                                        }else{ ?>
                                            
                                        <?php
                                        }
                                    ?>                                
                                    <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_pdf ?>" class="btn btn-submit-black" target="_blank" style="width: 50px">pdf</a>
                                </div>
                            </td>                            
                            <td><?= $a->username.' at '.$a->created_at; ?></td>   
                            <td><?= $a->duedate; ?></td>   
                            <td><?= ($a->first_hand) ? $a->first_hand : ' - '; ?></td>   
                            <td>
                                <?php 
                                    if ($a->upload_template_program) { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_template_program ?>"><?= $a->upload_template_program ?></a>
                                    <?php
                                    }else{ ?>
                                        <label class="form-label"><i>blank</i></label>    
                                    <?php
                                    }
                                ?>
                            </td>                   
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url().'management_claim/edit_registrasi_program/'.$a->signature ?>" class="btn btn-submit">edit</a>
                                    <a href="<?= base_url().'management_claim/delete_registrasi_program/'.$a->signature ?>" onclick="return confirm('Anda yakin menghapus data ini ?')" class="btn btn-submit-red" style="background-color: '#d9534f'">del</a>
                                </div>
                            </td>                               
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url().'management_claim/manage_registrasi_program/'.$a->signature ?>" class="btn btn btn-submit">site</a>
                                    <a href="<?= base_url().'management_claim/manage_registrasi_program_product/'.$a->signature ?>" class="btn btn-submit-red">product</a>      
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

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
                // scrollX: true
            });
        });
    </script>

    <script>    
        $("select[name = status_template]").on("change", function() {
            var status_template_terpilih = document.getElementById('status_template').value;
            let element = document.getElementById("mydiv");
            console.log(status_template_terpilih);
            if (status_template_terpilih == 1) { //jika ya
                document.getElementById("template_program").required = true;
                element.removeAttribute("hidden");
            }else{
                element.setAttribute("hidden", "hidden");
                document.getElementById('template_program').removeAttribute('required');
            }
        });
    </script>