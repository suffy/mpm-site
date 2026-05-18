</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>

                <div class="col">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><?= $title ?></h3>
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
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="nama_program">Tanggal Booking</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="input-group">
                                            <input type="date" id="date" class="form-control" name="date" required>
                                            <!-- <input type="date" name="from" id="from" class="form-control" value="<?= $from ?>" required>
                                            <input type="date" name="to" id="to" class="form-control" value="<?= $to ?>" required> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <input type="submit" value="Show Data" class="btn pastel-orange-btn">
                            </div>
                        </div>
                    </form>
                    <!-- end search -->

                    <!-- Booking Room -->
                    <?php 
                        foreach ($get_data_booking_group_tanggal->result() as $a) { ?>

                        <?php 
                            $get_semut = $this->model_ruang_meeting->get_data_booking_by_tanggal_and_room($a->tanggal, 1);
                            $get_gajah = $this->model_ruang_meeting->get_data_booking_by_tanggal_and_room($a->tanggal, 2);
                        ?> 

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row mt-1">
                                                <div class="col-md-12 mb-5 d-flex justify-content-center">
                                                    <h4><?= date('l', strtotime($a->tanggal)); ?>, <?= date('d M Y', strtotime($a->tanggal)); ?>

                                                    <?php
                                                    if ($a->tanggal < date('Y-m-d')) { ?>
                                                    <i>
                                                        <?php
                                                            echo ' (... '.date_diff(date_create(date('Y-m-d')), date_create($a->tanggal))->format('%a days ago').')';
                                                        ?>
                                                    </i>
                                                    <?php
                                                    }elseif ($a->tanggal == date('Y-m-d')) { ?>
                                                        <i>(... now)</i>
                                                    <?php
                                                    }else{ ?>
                                                    <i>
                                                    <?php
                                                        echo ' (... '.date_diff(date_create(date('Y-m-d')), date_create($a->tanggal))->format('%a days left').')';
                                                    ?>
                                                    </i>
                                                    <?php
                                                    }
                                                    ?>
                                                
                                                
                                                    </h4>
                                                </div>

                                                <div class="col-md-12  d-flex space-between gap-5">
                                                    
                                                    <div class="card mt-2" >
                                                        <div class="card-header">
                                                            <span class="icon">Ruang Semut</span>
                                                        </div>
                                                        <?php foreach ($get_semut->result() as $b) { ?>
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <h6 class="card-subtitle text-body-secondary"><?= $b->jam." - ".$b->username ?> </h6>
                                                                <h6 class="card-subtitle text-body-secondary"><a href="<?= base_url()."ruang_meeting/notulen/".$b->signature ?>" class="btn pastel-orange-btn" style="font-size: 12px; font-weight: 500; padding: 10px 10px 5px 10px; width: 100px">view notulen</a></h6>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>        
                                                    
                                                    <div class="card mt-2" >
                                                        <div class="card-header">
                                                            <span class="icon">Ruang Gajah</span>
                                                        </div>
                                                        <?php foreach ($get_gajah->result() as $b) { ?>
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <h6 class="card-subtitle text-body-secondary"><?= $b->jam." - ".$b->username ?></h6>
                                                                <h6 class="card-subtitle text-body-secondary"><a href="<?= base_url()."ruang_meeting/notulen/".$b->signature ?>" class="btn pastel-orange-btn" style="font-size: 12px; font-weight: 500; padding: 10px 10px 5px 10px; width: 100px">view notulen</a></h6>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>   


                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>




                        <?php
                        }
                    
                    ?>



                </div>
            </div>
        </div>
    </div>
</div>