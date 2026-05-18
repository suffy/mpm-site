</div>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <form action="<?= $url ?>">
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                <option value=""> -- pilih principal -- </option>
                <option value="all" <?= $this->input->get('supp') == 'all' ? 'selected' : '' ?>> All Principal </option>
                <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>
                <option value="005" <?= $this->input->get('supp') == 005 ? 'selected' : '' ?>> Ultra Sakti </option>
                <option value="012" <?= $this->input->get('supp') == 012 ? 'selected' : '' ?>> Intrafood </option>
                <option value="013" <?= $this->input->get('supp') == 013 ? 'selected' : '' ?>> Strive </option>
                <option value="015" <?= $this->input->get('supp') == 015 ? 'selected' : '' ?>> MDJ </option>
                <option value="025" <?= $this->input->get('supp') == 025 ? 'selected' : '' ?>> PT. GOOD PHARMA DERMATOLOGY </option>
                <option value="026" <?= $this->input->get('supp') == 026 ? 'selected' : '' ?>> PT. GUNUNG SUBUR SEJAHTERA </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode</label> 
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="pic" class="form-label">Nama PIC</label>
        </div>
        <div class="col-md-4">
            <select id="pic" name="pic" class="form-control" required>
                <option value="all"> All </option>
                <option value="18" <?= $this->input->get('pic') == '18' ? 'selected' : '' ?>> Ismi </option>
                <option value="444" <?= $this->input->get('pic') == '444' ? 'selected' : '' ?>> Ambar </option>
                <option value="561" <?= $this->input->get('pic') == '561' ? 'selected' : '' ?>> Adi </option>
                <option value="557" <?= $this->input->get('pic') == '557' ? 'selected' : '' ?>> Rani </option>
                <option value="99" <?= $this->input->get('pic') == '99' ? 'selected' : '' ?>> Yuli </option>
                <option value="812" <?= $this->input->get('pic') == '812' ? 'selected' : '' ?>> Dea </option>
                <option value="297" <?= $this->input->get('pic') == '297' ? 'selected' : '' ?>> Suffy </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
        </div>
        <div class="col-md-4">
            <select id="status" name="status" class="form-control" required>
                <option value=""> -- Pilih Status -- </option>
                <option value="all" <?= $this->input->get('status') == 'all' ? 'selected' : '' ?>> All </option>
                <option value="1" <?= $this->input->get('status') == '1' ? 'selected' : '' ?>> PENDING DP </option>
                <option value="2" <?= $this->input->get('status') == '2' ? 'selected' : '' ?>> PENDING MPM </option>
                <option value="3" <?= $this->input->get('status') == '3' ? 'selected' : '' ?>> REJECT MPM </option>
                <option value="4" <?= $this->input->get('status') == '4' ? 'selected' : '' ?>> PENDING PRINCIPAL </option>
                <option value="5" <?= $this->input->get('status') == '5' ? 'selected' : '' ?>> REJECT PRINCIPAL </option>
                <option value="6" <?= $this->input->get('status') == '6' ? 'selected' : '' ?>> APPROVE </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label"></label> 
        </div>
        <div class="col-md-10">
            <input type="submit" value="cari program" class="btn btn-submit-black">
            <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black">Reset View</a>
            <a href="<?= base_url().'assets/file/tutorial_ajuan_claim_20240418.pdf' ?>" class='btn btn-submit-red'>download tutorial claim</a>
        </div>
    </div>
    </form>

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

    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel-ajuan">
                    <thead>
                        <tr>
                            <th>Principal</th>
                            <th>No Surat</th>
                            <th>Nama Program</th>
                            <th>DP</th>
                            <th>Status Pelaporan</th>
                            <?php 
                            // jika yg login punya level 5 atau MPI, maka tidak tampil status internal
                                $level = $this->session->userdata('level');
                                if ($level == 4) { ?>

                                <?php
                                }else{ ?>                                
                                    <th width ="150px">Status Internal</th>  
                                <?php
                                }
                            ?>
                            <th>Status Hardcopy</th>
                            <th>Ikut Program ?</th>
                            <th>No Ajuan Claim</th>
                            <th>PIC</th>
                            <th>Jatuh Tempo</th>
                            <th>Kategori</th>
                            <th>Periode</th>                         
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->namasupp; ?></td>   
                            <td>
                                <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_pdf ?>" class="btn btn-submit-black" target="_blank"><?= (count(substr($a->nomor_surat, 0, 20) > 20) ? substr($a->nomor_surat, 0, 20).' ...' : $a->nomor_surat); ?></a>
                            </td>
                            <td><?= $a->nama_program; ?></td>
                            <td>
                                <?php 
                                    if ($a->nama_comp) { ?>
                                        <label class="form-label"><?= $a->nama_comp.' - '.$a->site_code; ?></label>
                                    <?php
                                    }else{ ?>
                                        <label class="form-label">Belum Mengajukan</label>
                                    <?php
                                    }
                                ?>                                
                            </td> 
                            <td>
                                <?php 
                                if ($a->status == 1) { // PROSES DP
                                    $color = "btn-submit-orange";
                                }elseif($a->status == 2){ // PROSES MPM
                                    $color = "btn-submit-red";
                                }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                                    $color = "btn-danger"; 
                                }elseif($a->status == 4){ // PROSES PRINCIPAL
                                    $color = "btn-submit";
                                }elseif($a->status == 5){ // PROSES KIRIM BARANG
                                    $color = "btn-info";
                                }elseif($a->status == 6){ // PROSES TERIMA BARANG
                                    $color = "btn-danger";
                                }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                                    $color = "btn-info";
                                }elseif($a->status == 8 || $a->status == 9){ // BARANG DITERIMA dan Pemusnahan
                                    $color = "btn-dark";
                                }elseif($a->status == 10){ // REJECT PRINCIPAL HO
                                    $color = "btn-dark";
                                }else{
                                    $color = "btn-submit-cream";
                                }
                                ?>

                                <?php 
                                    if ($a->status == null) { ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm">Belum ada</a>
                                    <?php
                                    }elseif($a->status == 99){ ?>
                                        <label class="btn btn-submit-black"><?= $a->nama_status_keikutsertaan ?></label>
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm"><?= $a->nama_status ?></a>
                                    <?php
                                    }
                                ?>
                            </td>    
                            <?php 
                                // jika yg login punya level 5 atau MPI, maka tidak tampil status internal
                                $level = $this->session->userdata('level');
                                if ($level == 4) { ?>

                                <?php
                                }else{ ?>
                                    <td>
                                        <?php 
                                            if ($a->status_internal) { ?>
                                                <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?>"><?= $a->nama_status_internal ?></a>                                        
                                            <?php
                                            }else{ ?>
                                                <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-black">Belum ada</a>
                                            <?php
                                            }
                                        ?>
                                    </td>
                                <?php
                                }
                            ?>
                            <td>
                                <?php 
                                    if ($a->status_hardcopy) { ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-red"><?= $a->nama_status_hardcopy ?></a>                                        
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-cream">Belum ada</a>
                                    <?php
                                    }
                                ?>
                            </td>       
                            <td align = 'center'>
                                <?php 
                                    if ($a->status_keikutsertaan == 1) { ?>
                                        <label class="btn btn-ikut"><?= $a->nama_status_keikutsertaan ?></label>
                                    <?php 
                                    }elseif ($a->status_keikutsertaan == null && $a->status == null) { ?>
                                        
                                        <a href="<?= base_url().'management_claim/flag_keikutsertaan/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-cream" onclick="return confirm('Apakah anda yakin tidak ingin claim program ini ?')">Tidak Ikut ? </a>
                                    <?php
                                    }elseif ($a->status_keikutsertaan == null && $a->status <> null) { ?>
                                        <label class="btn btn-ikut">Ya, saya ikut</label>
                                    <?php
                                    }                                    
                                    else{ ?>
                                        <a href="<?= base_url().'management_claim/flag_keikutsertaan_reset/'.$a->signature.'/'.$a->signature_ajuan ?>" class ="btn btn-submit-black" onclick="return confirm('Apakah anda yakin ingin reset pernyataan ini ?')"><?= $a->nama_status_keikutsertaan ?></a>
                                    <?php
                                    }
                                ?>
                            </td>                     
                            <td><?= $a->nomor_ajuan; ?></td>                                                  
                            <td><?= $a->username; ?></td>   
                            <td><?= $a->duedate; ?></td>    
                            <td><?= $a->kategori; ?></td>                            
                            <td><?= $a->from.' sd '.$a->to; ?></td>                  
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-ajuan').DataTable({
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