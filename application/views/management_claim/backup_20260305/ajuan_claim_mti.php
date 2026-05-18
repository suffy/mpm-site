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
                <select id="supp" name="supp" class="form-control">
                    <option value="all"> All </option>
                    <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                    <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>

                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>">
                    <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
            </div>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control" required>
                    <!-- <option value=""> -- Pilih Status -- </option> -->
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
            <div class="col-md-7">
                <input type="submit" value="cari program" class="btn btn-submit-black">
                <a href="<?= base_url().'management_claim/ajuan_claim_mti' ?>" class="btn btn-submit-black">Tampilkan ALL Data</a>
                <a href="<?= base_url().'management_claim/export_ajuan_claim_mti' ?>" class="btn btn-submit-black">Export</a>
            </div>
        </div>

    </form>

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

    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">
                <table id="example">
                <thead>
                    <tr>
                        <th class="text-center" colspan="5"> - Status Pelaporan - </th>
                        <th class="text-center" colspan="11"> - Detail Program - </th>
                    </tr>
                    <tr>
                        <th>NoSurat</th>
                        <th>Principal</th>
                        <th>Status Pelaporan</th>
                        <th>Status Hardcopy</th>      
                        
                        <?php 
                            // jika yg login punya level 5 atau MPI, maka tidak tampil status internal
                            $level = $this->session->userdata('level');
                            if ($level == 5) { ?>

                            <?php
                            }else{ ?>                                
                                <th width ="150px">Status Internal</th>  
                            <?php
                            }
                        ?>
                                              
                        <th>NoPelaporan</th>
                        <th>DP</th>
                        
                        <th>Nama KAM</th>
                        <th>Email KAM</th>
                        <th>Account</th>
                        <th>Area</th>
                        <th>Brand</th>
                        <th>Item</th>
                        <th style="width:200px">Mekanisme</th>
                        <th>Expose</th>
                        <th width="150px">Periode</th>                         
                    </tr>
                </thead>
                <tbody>     
                    <?php $no = 1;
                    foreach ($get_data->result() as $a) : ?>

                    <tr>          
                        <td><?= $a->nomor_surat ?></td>              
                        <td><?= $a->namasupp ?></td>
                        <td>
                            <?php 
                            if ($a->status_dp == 1) { // PENDING MPI
                                $color = "btn-submit-black";
                            }elseif($a->status_dp == 2){ // ON PROCESS
                                $color = "btn-submit-orange";
                            }elseif($a->status_dp == 3){ // APPROVE FINANCE
                                $color = "btn-submit";
                            }elseif($a->status_dp == 4){ // REJECT FINANCE
                                $color = "btn-submit-red";
                            }else{
                                $color = "btn-submit-black";
                            }
                            ?>

                            <?php 
                                if ($a->status == null) { ?>
                                    <a href="<?= base_url().'management_claim/routing_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm">Belum ada</a>
                                <?php
                                }elseif($a->status == 99){ ?>
                                    <label class="btn btn-tidakikut"><?= $a->nama_status_keikutsertaan ?></label>
                                <?php
                                }else{ ?>
                                    <a href="<?= base_url().'management_claim/routing_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm"><?= $a->nama_status_dp ?></a>
                                <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($a->status_hardcopy_dp == 1) { // PENDING MPI
                                $color = "btn-submit-black";
                            }elseif($a->status_hardcopy_dp == 2 || $a->status_hardcopy_dp == 3 || $a->status_hardcopy_dp == 4 || $a->status_hardcopy_dp == 5){
                                $color = "btn-submit-orange";
                            }elseif($a->status_hardcopy_dp == 6){ // approve
                                $color = "btn-submit";
                            }elseif($a->status_hardcopy_dp == 7){ // approve
                                $color = "btn-submit-red";
                            }else{
                                $color = "btn-submit-black";
                            }
                            ?>
                            <?php 
                                if ($a->status_hardcopy_dp) { ?>
                                    <a href="<?= base_url().'management_claim/routing_hardcopy_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?>"><?= $a->nama_status_hardcopy_dp ?></a>                                        
                                <?php
                                }else{ ?>
                                    <a href="<?= base_url().'management_claim/routing_hardcopy_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-black">Belum ada</a>
                                <?php
                                }
                            ?>
                        </td>
                        <?php 
                            // jika yg login punya level 5 atau MPI, maka tidak tampil status internal
                            $level = $this->session->userdata('level');
                            if ($level == 5) { ?>

                            <?php
                            }else{ ?>
                                <td>
                                    <?php 
                                        if ($a->status) { ?>
                                            <a href="<?= base_url().'management_claim/routing_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?>"><?= $a->nama_status ?></a>                                        
                                        <?php
                                        }else{ ?>
                                            <a href="<?= base_url().'management_claim/routing_mti/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-black">Belum ada</a>
                                        <?php
                                        }
                                    ?>
                                </td>
                            <?php
                            }
                        ?>
                        
                        <td><?= $a->nomor_ajuan ?></td>
                        <td>
                            <?php 
                                if ($a->nama_comp) { ?>
                                    <label class="form-label"><?= $a->nama_comp; ?></label>
                                <?php
                                }else{ ?>
                                    <label class="form-label">Belum Mengajukan</label>
                                <?php
                                }
                            ?>  
                        </td>
                        
                        <td><?= $a->name ?></td>
                        <td><?= $a->email ?></td>
                        <td><?= $a->account ?></td>
                        <td><?= $a->area ?></td>
                        <td><?= $a->brand ?></td>
                        <td><?= $a->item ?></td>
                        <td><?= $a->mekanisme ?></td>
                        <td><?= $a->expose ?></td>
                        <td><?= $a->from.' - '.$a->to.'' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": false,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
            scrollX: true
        });
    });
</script>