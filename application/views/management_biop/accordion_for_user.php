<style>
    #form {
        /* max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.5s ease, opacity 0.5s ease; */
    }

    #form.show {
        /* max-height: 100%; 
        opacity: 1;
        transition: all 0.15s ease-in-out;
        margin-top: 1rem;
        margin-bottom: 1rem; */
    }
</style>

<div class="container-fluid">
    <div class="col-md-12">
        
        <!-- Accordion -->
        <div class="row mt-2" id="form">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>No Pengajuan</label>
                                </div>
                                <div class="col-md-9">
                                    <label readonly>: <?= $get_biop->no_ajuan; ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label>User</label>
                                </div>
                                <div class="col-md-9">
                                    <label style="text-transform: capitalize;" readonly>: <?= $get_biop->pic_name; ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label>Jabatan</label>
                                </div>
                                <div class="col-md-9">
                                    <label readonly>: <?= $get_biop->jabatan; ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label>Periode</label>
                                </div>
                                <div class="col-md-9">
                                    <label readonly>: <?= date('d F Y', strtotime($get_biop->from)) . ' s/d ' . date('d F Y', strtotime($get_biop->to)); ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label>Tanggal Uang Keluar</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if($get_biop->tanggal_uang_keluar == null || $get_biop->tanggal_uang_keluar == '0000-00-00'){
                                            echo '<label readonly>: belum ada</label>';
                                        }else{
                                            echo '<label readonly>: ' . date('d F Y', strtotime($get_biop->tanggal_uang_keluar)) . '</label>';
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label>Status</label>
                                </div>
                                <div class="col-md-9">
                                    <label style="text-transform: capitalize;" readonly>: <?= $get_biop->nama_status; ?></label>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="table-responsive">
<table id="tabel-data-biop-accordion" class="display">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Keterangan Tempat</th>
            <th>Attachment</th>
            <th>NominalUser</th>
            <th>NominalAdmin</th>
            <th>NominalAtasan1</th>
            <th>NominalAtasan2</th>
            <th>NominalFinance</th>
            <th>NominalHeadFinance</th>
            <th>Admin</th>
            <th>Atasan1</th>
            <th>Atasan2</th>
            <th>Finance</th>
            <th>HeadFinance</th>
            <th>NoteAdmin</th>
            <th>NoteAtasan1</th>
            <th>NoteAtasan2</th>
            <th>NoteFinance</th>
            <th>NoteHeadFinance</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($get_data_biop as $biop) { ?>
        <tr>
            <td>
                <?php
                $date = new DateTime($biop->tanggal);
                echo $date->format('d M');
                ?>
            </td>
            <td style="text-transform: capitalize;"><?= $biop->nama_kategori; ?></td>
            <td><?= $biop->keterangan; ?></td>
            <td><?= $biop->keterangan_tempat; ?></td>
            <td>
                <?php
                    $attachment = json_decode($biop->attachment);
                    foreach ($attachment as $key_attachment) {?>
                        <a href="<?= base_url() . 'assets/uploads/management_biop/' .$biop->nama_kategori .'/'. $key_attachment ?>">
                            view</a>
                        <br>
                <?php } ?>
            </td>
            <td><?= number_format($biop->biaya); ?></td>
            <td><?= number_format($biop->biaya_admin_biop); ?></td>
            <td><?= number_format($biop->biaya_atasan1); ?></td>
            <td><?= number_format($biop->biaya_atasan2); ?></td>
            <td><?= number_format($biop->biaya_admin_finance); ?></td>
            <td><?= number_format($biop->biaya_head_finance); ?></td>
            <td>
                <?php if ($biop->flag_tolak_admin_biop == '0') { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                <?php
                } elseif ($biop->flag_tolak_admin_biop == '1') { ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
                <?php
                } else {
                    echo '';
                } ?>                        
            </td>
            <td>
                <?php if ($biop->flag_tolak_atasan1 == '0') { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                <?php
                } elseif ($biop->flag_tolak_atasan1 == '1') { ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
                <?php
                } else {
                    echo '';
                } ?>                        
            </td>
            <td>
                <?php if ($biop->flag_tolak_atasan2 == '0') { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                <?php
                } elseif ($biop->flag_tolak_atasan2 == '1') { ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
                <?php
                } else {
                    echo '';
                } ?>                        
            </td>
            <td>
                <?php if ($biop->flag_tolak_admin_finance == '0') { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                <?php
                } elseif ($biop->flag_tolak_admin_finance == '1') { ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
                <?php
                } else {
                    echo '';
                } ?>                        
            </td>
            <td>
                <?php if ($biop->flag_tolak_head_finance == '0') { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                <?php
                } elseif ($biop->flag_tolak_head_finance == '1') { ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
                <?php
                } else {
                    echo '';
                } ?>                        
            </td>
            <td><?= $biop->keterangan_admin_biop; ?></td>            
            <td><?= $biop->keterangan_atasan1; ?></td>            
            <td><?= $biop->keterangan_atasan2; ?></td>            
            <td><?= $biop->keterangan_admin_finance; ?></td>            
            <td><?= $biop->keterangan_head_finance; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>