</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">

<div class="card mt-2">
    <div class="card-body">
        <h5 class="card-title"><?= $title; ?></h5>

        <?php 
            if ($get_peserta->num_rows() == 0) { ?>
                <div class="alert alert-danger text-center mt-3" role="alert" style="border-radius: 15px;">
                    Anda belum mendaftarkan outlet apapun di program ini
                </div>
            <?php
            }else{ ?>

                <div class="card-block mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="peserta-loyalty" style="width: 100%">
                                <thead>
                                    <tr>   
                                        <th style="text-align: center;">Kode Outlet</th>         
                                        <th style="text-align: center;">SKP</th>          
                                        <th style="text-align: center;">Paket</th>         
                                        <th style="text-align: center; width: 10%;">download</th>         
                                        <th style="text-align: center;">Del</th>         
                                    </tr>
                                </thead>                        
                                <tbody>     
                                    <?php   
                                    foreach ($get_peserta->result() as $a) :                                     
                                    ?>
                                    <tr>                                        
                                        <td>
                                            <a href="<?= base_url() ?>management_claim/registrasi_peserta_loyalty_detail/<?= $signature_program ?>/<?= $a->signature_detail ?>" class="btn pending-finance" target="_blank"><?= $a->kode_outlet. ' - '. $a->nama_outlet; ?></a>
                                        <td>
                                            <?php if ($a->file_skp) { ?>
                                                <div class="text-center">
                                                    <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/'.$folder.'/'.$a->file_skp) ?>" target="_blank">
                                                        <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/'.$folder.'/'.$a->file_skp) ?>" alt="<?= $a->file_skp ?>" width="80" height="50" style="border: 2px solid black; border-radius: 5px;">
                                                    </a>                                
                                                </div>
                                            <?php }else{ ?>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="badge badge-danger">belum upload</span>
                                                </div>
                                            <?php
                                            } ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($a->paket) { ?>
                                                    <?= $a->paket ?>
                                                <?php    
                                                }else{ ?>
                                                    <span class="badge badge-danger">belum upload</span>
                                                <?php
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url().'management_claim/download_file_outlet/'.$a->signature_outlet.'/ktp' ?>" class="btn btn-submit-orange">ktp</a>
                                                <a href="<?= base_url().'management_claim/download_file_outlet/'.$a->signature_outlet.'/npwp' ?>" class="btn btn-submit-orange">npwp</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="<?= base_url() ?>management_claim/delete_peserta_loyalty/<?= $a->signature_detail.'/'.$signature_program ?>" class="delete-button" onclick="return confirm('Hapus Outlet ini ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php
            }
        ?>
    </div>
</div>

<?php 
    echo form_open($url); 
?>

<div class="card mt-5 mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= $title2; ?></h5>

        <div class="row mt-3">
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

        <?php 
            if ($get_master_outlet->num_rows() == 0) { ?>
                <div class="alert alert-danger text-center mt-3" role="alert" style="border-radius: 15px;">
                    database outlet masih kosong. Untuk menambahkan data outlet, silahkan klik link 
                    <a href="<?= base_url().'management_claim/generate_master_outlet' ?>" class="btn btn-submit-orange" target="_blank" style="border:none; margin-left: 5px">retrieve outlet based on sales</a>                    
                </div>
            <?php
            }else{ ?>

            <div class="card-block mt-3 mb-1">
                <div class="row">
                    <div class="col-md-12">
                        <table id="master-outlet">
                        <!-- <table class="modern-table"> -->
                            <thead>
                                <tr>   
                                    <th class="text-center" style="text-align: center; vertical-align: middle; width: 1%;">
                                        <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="width: 100%; font-size: 10px; background-color: darkslategray">
                                    </th>
                                    <th style="text-align: center;">File KTP</th>          
                                    <th style="text-align: center;">File NPWP</th>          
                                    <th style="text-align: center;">Alamat</th>    
                                    <th style="text-align: center;">Download</th>
                                </tr>
                            </thead>
                            <tbody>     
                                <?php   
                                foreach ($get_master_outlet->result() as $a) :                                     
                                ?>
                                <tr>
                                    <td>
                                        <center>                                            
                                        <input type="checkbox" name="id[]" value="<?= $a->id; ?>">
                                        </center>
                                    </td> 
                                    <td>
                                        <?php if ($a->file_ktp != '') { ?>
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_ktp) ?>" target="_blank">
                                                        <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_ktp) ?>" alt="<?= $a->file_ktp ?>" width="80" height="50" style="border: 2px solid black; border-radius: 5px">
                                                    </a>
                                                    <!-- <input type="hidden" name="id[]" value="<?= $a->id; ?>"> -->
                                                    <input type="hidden" name="file_ktp[<?= $a->id; ?>]" value="<?= $a->file_ktp; ?>">
                                                    <input type="hidden" name="no_ktp[<?= $a->id; ?>]" value="<?= $a->no_ktp; ?>">
                                                    <input type="hidden" name="file_npwp[<?= $a->id; ?>]" value="<?= $a->file_npwp; ?>">
                                                    <input type="hidden" name="no_npwp[<?= $a->id; ?>]" value="<?= $a->no_npwp; ?>">
                                                    <input type="hidden" name="id_program" value="<?= $id_program; ?>">
                                                    <input type="hidden" name="kode_outlet[<?= $a->id; ?>]" value="<?= $a->kode_outlet; ?>">
                                                </div>                                                    
                                                <div class="d-flex flex-column">
                                                    <span>Kode : <?= $a->kode_outlet; ?></span>
                                                    <span>Nama : <?= $a->nama_outlet; ?></span>
                                                    <span>Ktp: <?= $a->no_ktp; ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td style="text-align: left;">
                                        <?php if ($a->file_npwp != '') { ?>
                                            <div class="d-flex justify-content-left gap-2">
                                                <div>
                                                    <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_npwp) ?>" target="_blank">
                                                        <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_npwp) ?>" alt="<?= $a->file_npwp ?>" width="80" height="50" style="border: 2px solid black; border-radius: 5px">
                                                    </a>
                                                </div>                                                    
                                                <div>
                                                    <span>No : <?= $a->no_npwp; ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div>    
                                        <?php 
                                            if ($a->alamat) { ?>
                                            <span><?= $a->alamat; ?><?= " Telp : ".$a->no_telp; ?></span>
                                            <?php
                                            }
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url().'management_claim/download_file_outlet/'.$a->signature.'/ktp' ?>" class="btn btn-submit-orange">ktp</a>
                                            <a href="<?= base_url().'management_claim/download_file_outlet/'.$a->signature.'/npwp' ?>" class="btn btn-submit-orange">npwp</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>  
                            </tbody>
                        </table>

                        

                        
                        <div class="mt-3 d-flex gap-1">
                            <div>
                                <input type="hidden" name="site_code" value="<?= $site_code; ?>">
                                <input type="hidden" name="signature_program" value="<?= $signature_program; ?>">
                                <input type="submit" class="btn btn-submit-red" style="height: 50px; font-size: 20px" value="Daftarkan outlet terpilih">
                            </div>
                        </div>

                        <hr>
                        <div class="mt-5">

                            <div class="mt-3">
                                <div>
                                    <label for="">(*) untuk menuju ke Database Outlet. Klik "Database Outlet"</label>
                                </div>
                            </div>

                            <div>
                                <!-- <a href="<?= base_url().'management_claim/generate_master_outlet' ?>" class="btn btn-submit-black" style="height: 50px;  padding-top: 10px; font-size: 20px" target="_blank">Retrieve Outlet Based on Sales</a> -->
                                <a href="<?= base_url().'management_claim/master_outlet' ?>" class="btn btn-submit-black" style="height: 50px;  padding-top: 10px; font-size: 20px" target="_blank">Database Outlet</a>
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
<?= form_close(); ?>
<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#master-outlet').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });

        $('#peserta-loyalty').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });

    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("master-outlet"));
        XLSX.writeFile(convertedTable, "master-outlet.xlsx");
    }
</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>