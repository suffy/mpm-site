</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">
    


<?php echo form_open_multipart($url); ?>

<div class="card mt-2 mb-5">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

        <span class="btn pending-finance mt-2" style="padding: 10px 10px 0px 10px"><p>(*) Anda dapat melengkapi file KTP, NPWP, dan alamat dengan cara <strong>"Klik Kode Outlet di bawah ini"</strong></p></span>

        <div class="card-block mt-5">
            <div class="row">
                <div class="col-md-12">
                    <!-- <table id="tabel-ajuan" class="display table-striped table-bordered" style="display: inline-block; overflow-y: scroll; width: 100%;"> -->
                    <!-- <table id="tabel-registrasi-new"> -->
                    <table id="master-outlet" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; vertical-align: middle; width: 200px;">Branch</th>       
                                <th style="text-align: center; vertical-align: middle; width: 200px;">Kode Outlet</th>       
                                <th style="text-align: center; vertical-align: middle;">File-Ktp</th>           
                                <th style="text-align: center; vertical-align: middle;">File-Npwp</th>          
                                <th style="text-align: center;">Alamat</th>          
                                <!-- <th style="text-align: center; vertical-align: middle; width: 1px;">Del</th>         -->
                            </tr>
                        </thead>
                        
                        <?php 
                            if ($get_data) { ?>
                                
                                <tbody>     
                                    <?php   
                                    foreach ($get_data->result() as $a) :                                     
                                    ?>
                                    <tr>
                                        <td><?= $a->branch_name.' - '.$a->nama_comp; ?></td>
                                        <td style="vertical-align: top;">                                            
                                            <a href="<?= base_url() ?>management_claim/master_outlet_detail/<?= $a->signature ?>" class="btn pending-finance" target="_blank" style="width: 200px;"><?= $a->kode_outlet. ' - '. $a->nama_outlet; ?></a>
                                            <input type="hidden" name="site_code" value="<?= $a->site_code; ?>">
                                        </td>
                                        <td>
                                            <?php if ($a->file_ktp) { ?>
                                                <div class="d-flex flex-row gap-2">
                                                    <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_ktp) ?>" target="_blank">
                                                        <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_ktp) ?>" alt="<?= $a->file_ktp ?>" width="80" height="50" style="border: 2px solid black; border-radius: 5px">
                                                    </a>                                                
                                                    <span>No. <?= $a->no_ktp; ?></span>
                                                </div>
                                            <?php }else{ ?>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="badge badge-danger">belum upload</span>
                                                </div>
                                            <?php
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($a->file_npwp) { ?>
                                                <div class="d-flex flex-row gap-2 jus">
                                                    <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_npwp) ?>" target="_blank">
                                                        <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$a->file_npwp) ?>" alt="<?= $a->file_npwp ?>" width="80" height="50" style="border: 2px solid black; border-radius: 5px;">
                                                    </a>                                                
                                                    <span>No. <?= $a->no_npwp; ?></span>
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
                                                if ($a->alamat) { ?>
                                                <div class="align-top">
                                                    <span class="align-top"><?= $a->alamat; ?> Telp : <?= $a->no_telp; ?></span>
                                                </div>
                                                <?php
                                                }else{ ?>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="badge badge-danger align-middle">belum ada data</span>
                                                    </div>
                                                <?php
                                                }
                                            ?>
                                        </td>
                                        <!-- <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="<?= base_url() ?>management_claim/delete_master_outlet/<?= $a->kode_outlet ?>" class="delete-button" onclick="return confirm('Hapus Outlet ini ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                            </div>
                                        </td> -->
                                    </tr>
                                    <?php endforeach; ?>  
                                </tbody>
                            <?php
                            }
                        ?>
                    </table>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <label for="">(*) untuk menambahkan data outlet baru selain yang tertera di atas, silahkan klik "Retrive Outlet Based on Sales"</label>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-1">
                        <div>
                            <button type="button" class="btn btn-submit-cream" onclick="convertTable()" style="height: 50px;">Convert to Excel</button>
                        </div>
                        <div>
                            <a href="<?= base_url().'management_claim/generate_master_outlet' ?>" class="btn btn-submit-black" style="height: 50px;" target="_blank">Retrive Outlet Based on Sales</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#master-outlet').DataTable({
            "pageLength": 100,
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