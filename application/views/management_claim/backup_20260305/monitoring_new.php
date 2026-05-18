<?php
$this->load->view('management_claim/monitoring_form');
?>

<div class="container-fluid mt-4">


<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Summary By Status</h5>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <div class="card-block mt-1 mb-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tabel1" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Status Internal</th>                  
                                                <th>Total</th>                  
                                                <!-- <th class="text-center">#</th>                   -->
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php
                                            foreach ($data['data']->result() as $a) : ?>
                                            <tr>
                                                <td><?= $a->nama_status_internal; ?></td>  
                                                <td><?= $a->count; ?></td>                                          
                                                <!-- <td>
                                                    <a href="#" class="btn btn-submit-cream">Show</a>
                                                </td>                                           -->
                                            </tr>
                                            <?php endforeach; ?>   
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

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Summary By Status & Principal</h5>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <div class="card-block mt-1 mb-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tabel2" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Principal</th>                  
                                                <th>Status Internal</th>                  
                                                <th>Total</th>                  
                                                <!-- <th class="text-center">#</th>                   -->
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php
                                            foreach ($data['data_by_principal']->result() as $a) : ?>
                                            <tr>
                                                <td><?= $a->namasupp; ?></td>  
                                                <td><?= $a->nama_status_internal; ?></td>  
                                                <td><?= $a->count; ?></td>                                          
                                                <!-- <td>
                                                    <a href="#" class="btn btn-submit-cream">Show</a>
                                                </td>                                           -->
                                            </tr>
                                            <?php endforeach; ?>   
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

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Summary By Status, Principal, Kategori</h5>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <div class="card-block mt-1 mb-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tabel3" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Principal</th>                  
                                                <th>Kategori</th>                  
                                                <th>Status Internal</th>                  
                                                <th>Total</th>                  
                                                <!-- <th class="text-center">#</th>                   -->
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php
                                            foreach ($data['data_by_principal_kategori']->result() as $a) : ?>
                                            <tr>
                                                <td><?= $a->namasupp; ?></td>  
                                                <td><?= $a->nama_kategori; ?></td>  
                                                <td><?= $a->nama_status_internal; ?></td>  
                                                <td><?= $a->count; ?></td>                                          
                                                <!-- <td>
                                                    <a href="#" class="btn btn-submit-cream">Show</a>
                                                </td>                                           -->
                                            </tr>
                                            <?php endforeach; ?>   
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

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Summary By Status, Principal, Kategori, No Claim</h5>
                    </div>
                    <div>
                        <span class="btn btn-submit-orange pt-2 pb-2" style="cursor: pointer; font-size: 13px; font-weight: bold;border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" onclick="convertTable()">Export</span>
                    </div>
                </div>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <div class="card-block mt-1 mb-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tabel4">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 100px">Principal</th>    
                                                <th class="text-center" style="width: 150px">Kategori</th>                  
                                                <th class="text-center" style="width: 150px">Status Internal</th>                  
                                                <th class="text-center" style="width: 150px">No Claim</th>                  
                                                <th class="text-center" style="width: 150px">DP</th>                     
                                                <th class="text-center" style="width: 150px">No Surat</th>                     
                                                <th class="text-center" style="width: 150px">Nama Program</th>                     
                                                <th class="text-center" style="width: 150px">PIC on Duty</th>                            
                                                <th class="text-center" style="width: 150px">duedate Response</th>                            
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php
                                            foreach ($data['data_by_principal_kategori_noajuan']->result() as $a) : ?>
                                            <tr>
                                                <td><?= $a->namasupp; ?></td>  
                                                <td><?= $a->nama_kategori; ?></td>  
                                                <td><?= $a->nama_status_internal; ?></td>  
                                                <td>
                                                    <a href="<?= base_url().'management_claim/routing/'.$a->signature_program.'/'.$a->signature_ajuan ?>" class="btn btn-submit-cream" target="_blank"><?= $a->nomor_ajuan; ?></a>
                                                </td>  
                                                <td><?= $a->branch_name." - ".$a->nama_comp." - ".$a->site_code; ?></td>  
                                                <td><?= $a->nomor_surat; ?></td>  
                                                <td><?= $a->nama_program; ?></td>  
                                                <td><?= $a->username; ?></td>                                        
                                                <td>
                                                    <?php 
                                                        if ($a->duedate_response) { 
                                                            if ($a->duedate_response < date('Y-m-d')) { ?>
                                                                <span for="" class="pending-finance" style="font-size: 12px; padding: 5px;border-radius: 5px">
                                                                <?php
                                                                    echo date('d M y', strtotime($a->duedate_response)). ' ('.date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days ago').')';
                                                                ?>
                                                                </span>
                                                            <?php
                                                            }else{ ?>
                                                            <span for="" class="pending-scm" style="font-size: 12px; padding: 5px;border-radius: 5px">
                                                            <?php
                                                                echo date('d M y', strtotime($a->duedate_response)). ' ('.date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days left').')';
                                                            ?>
                                                            </span>
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                </td>                                        
                                            </tr>
                                            <?php endforeach; ?>   
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
    

</div>
                                


<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel1').DataTable({
            "pageLength": 5,
            "ordering": true,
            "info": false,
            "dom": 'rtip',
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
        $('#tabel2').DataTable({
            "pageLength": 5,
            "ordering": true,
            "info": false,
            // "dom": 'rtip',
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
        $('#tabel3').DataTable({
            "pageLength": 5,
            "ordering": true,
            "info": false,
            // "dom": 'rtip',
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
        $('#tabel4').DataTable({
            "pageLength": 5,
            "ordering": true,
            // "info": false,
            // "dom": 'rtip',
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });


    });


</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel4"));
        XLSX.writeFile(convertedTable, "export summary claim by status-kategori-nomorclaim.xlsx");
    }
</script>