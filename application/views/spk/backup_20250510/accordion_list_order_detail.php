<style>
    textarea,input {
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

<div class="mt-2">
    <?= $this->load->view('spk/component/title');?>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <p>
                <button class="btn btn-submit-black" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Lihat Data PO</button>
            </p>
            <div class="row">
                        
                <div class="col-md-9">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">     

                                <div class="row mt-1">
                                    <div class="col-lg-12">
                                        <label for="supp"><strong>Data PO</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-3">
                                        <label for="id_po">Id PO</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <label  readonly><?= $id_po ?></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-3">
                                        <label for="nopo">Nopo</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <label  readonly><?= $nopo ?></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-3">
                                        <label for="supp">Principal</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <label  readonly><?= $namasupp ?></label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="branch_name" >Branch</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="branch_name"  value="<?= $branch_name ?>" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="nama_comp" >SubBranch</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="nama_comp"  value="<?= $nama_comp.' - '.$kode_alamat ?>" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="company" >Company</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="company"  value="<?= $company ?>" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="npwp" >NPWP</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="npwp"  value="<?= $npwp ?>" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="email" >Email</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea name="email"  cols="30" rows="3" readonly><?= $email ?></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="alamat" >Alamat</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea name="alamat"  cols="30" rows="5" readonly><?= $alamat ?></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="alamat_kirim" >Alamat Kirim</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea name="alamat_kirim"  cols="30" rows="5" readonly><?= $alamat_kirim ?></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="status" >Flag Status</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <?php 
                                            if ($status == 2 && $status_approval == 1 && $flag_open == 1) { ?>
                                                <input type="text"  value="pending rilis" readonly>
                                            <?php
                                            }elseif ($status == 2 && $status_approval == 1 && $flag_open == 0){ ?>
                                                <input type="text"  value="pending finance" readonly>
                                            <?php
                                            }else{ ?>
                                                <input type="text"  value="pending scm" readonly>
                                            <?php
                                            }
                                        ?>

                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="flag_open" >Flag OPEN Finance</label> 
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="flag_open"  value="<?= ($flag_open == 0) ? 'LOCK' : 'OPEN'. ' - '.$open_date ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="tipe" >Tipe</label> 
                                    </div>
                                    <div class="col-lg-9">
                                    <input name="tipe"  value ="<?php
                                        if ($tipe == 'A') {
                                            echo 'Alokasi';
                                        } elseif ($tipe == 'R'){
                                            echo 'Replenishment';
                                        } else {
                                            echo 'SPK';
                                        }
                                    ?>" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>
</div>
