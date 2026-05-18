<style>
    td {
        text-align: center;
        font-size: 17px;
        height: 40px;
    }

    th {
        text-align: center;
        font-size: 20px;
    }
</style>

</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>
                <div class="col">
                    <div class="row">
                        <div class="col-md-12 az-content-label">
                            <?= $title ?>
                        </div>
                    </div>

                    <!-- SESSION FLASH-->
                    <div class="row mt-2">
                        <div class="col-8">
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
                    <!-- END SESSION FLASH -->

                    <!-- Search -->
                    <form action="<?= base_url($url); ?>" method="GET">
                        <div class="row mt-5">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="nama_program">Tanggal Booking</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="date" id="date" class="form-control" name="date" value="<?= $this->input->get('date'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <input type="submit" value="Show Data" class="btn pastel-orange-btn">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end search -->
                
                    <!-- Booking Room -->
                    <div class="row mt-5">
                        <div class="table-responsive col-md-6">
                            <?php echo form_open($url_add); ?>
                            <table id="tabel-data"
                                style="text-align: center; background-color: gray !important;">
                                <thead >
                                    <tr>
                                        <th colspan="3" class="text-center">Semut at <?= date('d M Y', strtotime($date)); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">
                                            <button type="button" class="btn btn-default btn-lg " id="semut"
                                            value="pilih semut" onclick="click_ruang_semut()"><b>Status</b></button>
                                        </th>
                                        <th class="text-center">Del</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($data_ruang_meeting_semut->result() as $a) : ?>
                                    <tr>
                                        <td><?= $a->jam;?></td>
                                        <td>
                                            <?php 
                                            if ($a->booking_by == null) { ?> 
                                                <center>
                                                    <input type="text" name="room" value="1" hidden>
                                                    <input type="checkbox" name="jam_id[]" class="semut" value="<?= $a->jam_id; ?>" style="width: 20px; height: 20px;">
                                                    <input type="text" name="date" value="<?= $date; ?>" hidden>
                                                </center>
                                            <?php
                                            }else{ ?>
                                                <span>Booked by <?= $a->username; ?></span>
                                            <?php
                                                }
                                            ?> 
                                        </td>
                                        <?php 
                                        if($a->id) {?>
                                            <?php 
                                            if($session == $a->username || $session == 'ratri') {?>
                                            <td>
                                                <a href="<?= base_url() ?>ruang_meeting/delete_booking/<?= $a->id; ?>/<?= $a->signature; ?>" class="delete-button" onclick="return confirm('Cancel Booking ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                            </td>
                                            <?php
                                            }else{?>
                                                <td>
                                                    <label style="font-size:14px; color: #006A67;" ></label>
                                                </td>
                                            <?php
                                            }?>
                                        <?php
                                        }else{?>
                                            <td>
                                                <label style="font-size:14px; color: #006A67;" ></label>
                                            </td>
                                        <?php
                                        }?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="row mb-5 mt-3">
                                <div class="col-lg-12 d-flex justify-content-center btn-group">
                                    <?php 
                                        if ($get_count_booking_semut != 8) { ?> 
                                            <button type="submit" class="btn btn-submit-orange" value="1" name="submit" style="border-radius: 15px; height: 45px;">Booking Ruang Semut</button>
                                            <?php
                                        }else{ ?>
                                            <button type="submit" class="btn btn-submit-orange" value="0" name="submit" disabled>Booking Ruang Semut</button>
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <?= form_close(); ?>
                        </div>
                        
                        <!-- Table Gajah -->
                        <div class="table-responsive col-md-6">
                            <?php echo form_open($url_add); ?>
                            <table id="tabel-data2"
                                style="text-align:center;">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">Gajah at <?= date('d M Y', strtotime($date)); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">
                                            <button type="button" class="btn btn-default btn-lg " id="gajah"
                                            value="pilih gajah" onclick="click_ruang_gajah()"><b>Status</b></button>
                                        </th>
                                        <th class="text-center">Del</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($data_ruang_meeting_gajah->result() as $key) : ?>
                                    <tr>
                                        <td><?= $key->jam;?> </td>
                                        <td>
                                            <?php 
                                            if ($key->booking_by == null) { ?> 
                                                <center>
                                                    <input type="text" name="room" value="2" hidden>
                                                    <input type="checkbox" name="jam_id[]" class="gajah" value="<?= $key->jam_id; ?>" style="width: 20px; height: 20px;">
                                                    <input type="text" name="date" value="<?= $date; ?>" hidden>
                                                </center>
                                            <?php
                                            }else{ ?>
                                                <span>Book by <?= $key->username; ?></span>
                                            <?php
                                                }
                                            ?> 
                                        </td>
                                        <?php 
                                        if($key->id) {?>
                                            <?php 
                                            if($session == $key->username || $session == 'ratri') {?>
                                            <td>
                                                <a href="<?= base_url() ?>ruang_meeting/delete_booking/<?= $key->id; ?>/<?= $key->signature; ?>" class="delete-button" onclick="return confirm('Cancel Booking ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                            </td>
                                            <?php
                                            }else{?>
                                                <td>
                                                    <label style="font-size:14px; color: #006A67;" ></label>
                                                </td>
                                            <?php
                                            }?>
                                        <?php
                                        }else{?>
                                            <td>
                                                <label style="font-size:14px; color: #006A67;" ></label>
                                            </td>
                                        <?php
                                        }?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="row mb-5 mt-3">
                                <div class="col-lg-12 d-flex justify-content-center btn-group">
                                <?php 
                                        if ($get_count_booking_gajah != 8) { ?> 
                                            <button type="submit" class="btn btn-submit-orange" value="1" name="submit" style="border-radius: 15px; height: 45px;" >Booking Ruang Gajah</button>
                                            <?php
                                        }else{ ?>
                                            <button type="submit" class="btn btn-submit-orange" value="0" name="submit" disabled>Booking Ruang Gajah</button>
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                    <!-- end table -->
                    <!-- End Booking Room -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
        });
    });

    $(document).ready(function () {
        $('#tabel-data2').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    function ValidateCompare() {
    var c = document.getElementsByName("options[]");
    var count = 0;
    for (var i = 0; i < c.length; i++) {
        if (c[i].checked) {
        count++;
        }
    }
    if (count < 1) {
        alert("Anda belum memilih satu datapun.");
        return false;
    }
    return true;
    }
</script>