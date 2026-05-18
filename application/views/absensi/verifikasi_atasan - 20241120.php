</div>
<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>
                <div class="col ml-5">
                    <!-- Verifiaksi Atasan -->
                    <div class="row">
                        <div class="col-md-12 az-content-label">
                            <?= $title ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <form action="<?= base_url($url); ?>" method="post">
                                <input type="text" class="form-control form-control-md" id="signature" name="signature"
                                    value="<?= $signature ?>" hidden>

                                <div class="col-md-12 mt-4">
                                    <label for="biaya" class="form-label">Approve atau Reject ?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_verifikasi"
                                            id="status_verifikasi1" value="1" checked>
                                        <label class="form-check-label" for="status_verifikasi1">
                                            Approve
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_verifikasi"
                                            id="status_verifikasi2" value="0">
                                        <label class="form-check-label" for="status_verifikasi2">
                                            Reject
                                        </label>
                                    </div>

                                    <div class="mt-2">
                                        <label for="biaya" class="form-label">Keterangan</label>
                                        <textarea name="verifikasi_ket" id="verifikasi_ket"
                                            class="form-control"></textarea>
                                    </div>
                                    <div class="mt-2">
                                        <label for="signature" class="form-label">Manage Signature Digital</label>
                                        <div class="d-flex flex-row">
                                            <?php 
                                                $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                                                if (file_exists($file)) { ?>
                                            <a href="<?= base_url($url2) ?>" class="btn btn-submit-black"
                                                target="_blank">
                                                <img src="<?= base_url().'assets/uploads/signature/'.$this->session->userdata('username').'-signature.png' ?>"
                                                    alt="<?= $this->session->userdata('username').'-signature' ?>"
                                                    width="150px">
                                            </a>
                                            <?php
                                                } else { ?>
                                            <a href="<?= base_url($url2) ?>" class="btn btn-submit-black"
                                                target="_blank">
                                                click here
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class=" mt-2 mb-5">
                                        <label for="keterangan" class="form-label"></label>
                                        <div class="d-flex flex-row">
                                            <?php 
                                                $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                                                if (file_exists($file)) 
                                                { 
                                                    if ($this->session->userdata('id') == $userid_verifikasi1) 
                                                    {
                                                        if (1 == 1) 
                                                        { 
                                                            echo '<input type="submit" value="Submit Verifikasi" class="btn btn-submit-black">';
                                                        }else
                                                        { 
                                                            echo '<button type="submit" class="btn btn-submit" disabled>Verifikasi anda sudah masuk</button>';
                                                        }
                                                    } else 
                                                    {
                                                        echo '<button type="submit" class="btn btn-submit" disabled>not your authority</button>';
                                                    }
                                                }else
                                                { 
                                                    echo '<button type="submit" class="btn btn-submit" disabled>verifikasi tidak bisa dilakukan. Mungkin anda belum mengisi signature.</button>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md mt-4">
                            <h4>Ringkasan Absensi</h4>
                            <table class="table table-striped table-borderless">
                                <tr>
                                    <td>Hadir Hari Kerja</td>
                                    <td><?= $total_hadir; ?></td>
                                </tr>
                                <tr class="table-info">
                                    <td>Terlambat</td>
                                    <td><?= $total_terlambat; ?></td>
                                </tr>
                                <tr>
                                    <td>No Information</td>
                                    <td><?= $no_information; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end verifiaksi atasan -->


                    <!-- table data absensi -->
                    <div class="row mt-2 ms-5">
                        <div class="az-content-label text-center">
                            Catatan Kehadiran
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table id="tabel-data" class="display table-striped table-bordered" style="width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center;width: 120px;">Nama Karyawan</th>
                                        <th rowspan="2" style="text-align: center;width: 50px;">Tanggal</th>
                                        <th colspan="2" style="text-align: center;">Jadwal Kerja</th>
                                        <th colspan="2" style="text-align: center;">Kehadiran Harian</th>
                                        <th rowspan="2" style="text-align: center; width: 100px;">Total Jam Kerja</th>
                                        <th rowspan="2" style="text-align: center;width: 120px;">Status Kehadiran</th>
                                        </th>
                                        <th rowspan="2" style="text-align: center;">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;width: 20px;">Masuk</th>
                                        <th style="text-align: center;width: 20px;">Keluar</th>
                                        <th style="text-align: center;width: 100px;">Jam Masuk</th>
                                        <th style="text-align: center;width: 100px;">Jam Keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_absensi->result() as $a) : ?>
                                    <tr>
                                        <td><?= $a->name;?></td>
                                        <td><?= $a->tanggal; ?></td>
                                        <td><?= $a->jam_masuk_kantor; ?></td>
                                        <td><?= $a->jam_keluar_kantor; ?></td>
                                        <td><?= $a->actual_masuk; ?></td>
                                        <td><?= $a->actual_keluar; ?></td>
                                        <td><?= $a->total_jam_kerja; ?></td>
                                        <td>
                                            <?php 
                                                if ($a->status_hari == 6 || $a->status_hari == 0) { ?>
                                                    <label style="font-size:14px; color: #006A67; ">Weekend</label>
                                                <?php
                                                }else{
                                                    if ($a->flag_terlambat == 1) { ?>
                                                        <label class="status pending-finance" style="font-size:14px">Terlambat</label>
                                                    <?php }else
                                                    { 
                                                        if ($a->actual_masuk == "" || $a->actual_keluar == "") 
                                                        { ?>
                                                            <label class="status pending-finance" style="font-size:14px">No Info</label>
                                                        <?php
                                                        }else
                                                        { 
                                                            ?>
                                                                <label style="font-size:14px; color: #608BC1; ">Hadir</label>
                                                            <?php
                                                        }

                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td style="text-align: left;">
                                            
                                            <?php                                                     
                                            if ($a->status_hari == 6 || $a->status_hari == 0) 
                                            { ?>
                                                <label style="font-size:14px; color: #006A67; ">-</label>
                                            <?php 
                                            }else
                                            {
                                                if ($a->flag_terlambat == 1  && $a->userid == $this->session->userdata('id')) 
                                                { 
                                                    if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                    { ?>
                                                        <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                        <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="Input Keterangan ... ?"><?= $a->keterangan; ?></textarea>
                                                    <?php
                                                    }else{
                                                        echo $a->keterangan;

                                                    }                                                                                                                    
                                    
                                                } else 
                                                {  
                                                    if ($a->actual_masuk == "" || $a->actual_keluar == "") 
                                                    { 
                                                        if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                        { ?>
                                                            <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                            <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="Input Keterangan ... ?"><?= $a->keterangan; ?></textarea>
                                                        <?php
                                                        }else{ ?>
                                                                <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                
                                                        
                                                        <?php
                                                        }    
                                                    }else
                                                    {  ?>
                                                        <label style="font-size:14px; color: #006A67;" >-</label>
                                                    <?php
                                                    } ?>                                                            
                                                <?php 
                                                } 
                                            }?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end table -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data').DataTable({
            "info": false,
            "paging": false,
            // scrollX: true
        });
    });
</script>