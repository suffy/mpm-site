</div>

<div class="container-fluid mb-5">
    
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
        <div class="col-md-5">
            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001"> Deltomed </option>
                <option value="002"> Marguna </option>
                <option value="005"> Ultra Sakti </option>
                <option value="012"> Intrafood </option>
                <option value="013"> Strive </option>
                <option value="015"> MDJ </option>
                <option value="025"> PT. GOOD PHARMA DERMATOLOGY </option>
                <option value="026"> PT. GUNUNG SUBUR SEJAHTERA </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode Program</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" required>
            <input class="form-control form-control-md" id="from" type="date" name="to" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="kategori" class="form-label">Kategori</label>
        </div>
        <div class="col-md-5">
            <select id="kategori" name="kategori" class="form-control" required>
                <option value=""> -- pilih kategori -- </option>
                <option value="loyalty"> Loyalty </option>
                <option value="bonus_barang"> Bonus Barang</option>
                <option value="diskon_herbal"> Diskon Herbal</option>
                <option value="diskon_candy"> Diskon Candy</option>
                <option value="diskon"> Diskon</option>
                <option value="insentif"> Insentif </option>
                <option value="listing_fee"> Listing Fee </option>
                <option value="rafaksi"> Rafaksi </option>
                <option value="program MT"> Program MT </option>
                <option value="sewa_display"> Sewa Display </option>
                <option value="salesman_herbana"> Salesman Herbana </option>
                <option value="sample_promosi"> Sample Promosi </option>
                <option value="delto_corner"> Delto Corner </option>
                <option value="support_mailer"> Support Mailer </option>
                <option value="kompetisi"> Kompetisi / Reward </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="status_template" class="form-label">Lampirkan Template tambahan</label>
        </div>
        <div class="col-md-5">
            <select id="status_template" name="status_template" class="form-control" required>
                <option value=""> -- pilih status template -- </option>
                <option value=1> Ya </option>
                <option value=0> Tidak</option>
            </select>
        </div>
    </div>

    <div class="row mt-3" hidden id="mydiv">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Template Program</label>
        </div>
        <div class="col-md-5">
            <input class="form-control form-control-md" type="file" id="template_program" name="template_program">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Nomor Surat Program</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="nomor_surat" type="text" name="nomor_surat" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program" class="form-label">Nama Program</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="nama_program" type="text" name="nama_program" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="syarat" class="form-label">Syarat Ketentuan</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <textarea class="form-control" id="syarat" name="syarat" cols="5" rows="5" required></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="duedate" class="form-label">Deadline Ajuan Claim</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="duedate" type="date" name="duedate" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen (.jpg)</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="upload_jpg" type="file" name="upload_jpg">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_pdf" class="form-label">Upload Dokumen (.pdf)</label>
        </div>
        <div class="col-md-5 d-flex flex-row">
            <input class="form-control form-control-md" id="upload_pdf" type="file" name="upload_pdf" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="first_hand" class="form-label">First Hand</label>
        </div>
        <div class="col-md-5">
            <select id="first_hand" name="first_hand" class="form-control" required>
                <option value=""> -- Pilih First Hand -- </option>
                <option value='mpm'> PIC MPM</option>
                <option value='principal'> PIC Principal</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-5 d-flex flex-row">
            <button type="submit" class="btn btn-submit-black">Save Registrasi Program</button>
        </div>
    </div>
</form>



<div class="container-fluid mt-5">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <h4 class="title-square">List Program dan Multiple Perubahan Deadline</h4>
        </div>
    </div>

    <form action="<?= $url_search ?>" method="GET">

    <div class="row mt-4">
        <div class="col-md-2">
            <label>Periode Program </label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" name="from" id="from" class="form-control" value="<?= $this->input->get('from') ?>" required>
                <input type="date" name="to" id="to" class="form-control" value="<?= $this->input->get('to') ?>" required>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program"></label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="submit" value="Search Data" class="btn btn-submit-black">
            </div>
        </div>
    </div>
    </form>

    <?php echo form_open($url_deadline); ?>

    <div class="card-block mt-1 mb-5">
        <div class="row">        
            <div class="col-md-12 mt-4">                
                <table id="tabel-registrasi">
                    <thead>
                        <tr>
                            <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                            </th>
                            <th class="text-center">Principal</th>
                            <th class="text-center">NomorSurat</th>
                            <th class="text-center">NamaProgram</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">Syarat</th>
                            <th class="text-center">UpdatedBy</th>
                            <th class="text-center">Deadline</th>
                            <th class="text-center">First Hand</th>
                            <th class="text-center">Dokumen</th>
                            <th class="text-center">TemplateTambahan</th>
                            <th class="text-center">Del</th>                            
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                        foreach ($get_registrasi_program->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                                </center>
                            </td> 
                            <td><?= $a->namasupp; ?></td>
                            <td><?= $a->nomor_surat; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td><?= $a->kategori; ?></td>
                            <td><?= $a->from.' sd '.$a->to; ?></td>
                            <td width ="150px"><?= $a->syarat; ?></td>
                            <td><?= $a->username.' at '.$a->created_at; ?></td>   
                            <td><?= $a->duedate; ?></td>   
                            <td><?= ($a->first_hand) ? $a->first_hand : ' - '; ?></td>   
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
                            <td align="center">
                                <?php 
                                    if ($a->upload_template_program) { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_template_program ?>" class="btn btn-submit-black"><?= $a->upload_template_program ?></a>
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

    <div class="row mt-5">
        <div class="col-md-12"><strong>
            Cara mengubah deadline : <br>
            1. Ceklist program yang ingin diubah <br>
            2. Masukkan tanggal deadline dan Klik Update Deadline<br></strong>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Deadline yang diinginkan </label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" name="deadline" class="form-control" required>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-md-2">
            <label></label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="submit" value="Update Data Deadline" class="btn btn-submit-black">
            </div>
        </div>
    </div>

    <?= form_close(); ?>

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

    <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>