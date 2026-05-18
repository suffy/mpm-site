</div>
<div class="container-fluid">

<div class="row mb-4">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<?php echo form_open($url); ?>
<div class="card-block mt-1 mb-1">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel-data" class="display" style="overflow-y: scroll; height:100px; width: 100%;">
                <thead>
                    <tr>
                        <!-- <th width="10%">Principal</th>  
                        <th>Branch</th>  
                        <th>NoSurat</th>  
                        <th>NoAjuan</th>   -->
                        <th>DeadlineClaim</th>  
                        <th>User</th> 
                        <th>TanggalClaim</th>  
                        <th>CreatedAt</th>  
                        <th>Status</th>  
                        <th>Status Internal</th>                         
                        <th>Keterangan</th> 
                        <th>File</th>
                </thead>
                <tbody>     
                    <?php
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <!-- <td><?= $a->namasupp; ?></td>  
                        <td><?= $a->branch_name.' - '.$a->nama_comp.' ('.$a->site_code.')'; ?></td>  
                        <td><?= $a->nomor_surat; ?></td>  
                        <td><?= $a->nomor_ajuan; ?></td>   -->
                        <td><?= $a->duedate; ?></td>  
                        <td><?= $a->username; ?></td> 
                        <td><?= $a->tanggal_claim; ?></td>  
                        <td><?= $a->created_at; ?></td>  
                        <td><?= $a->nama_status; ?></td>  
                        <td><?= $a->nama_status_internal; ?></td>                           
                        <td><?= $a->keterangan; ?></td>  
                        <td>
                            <?php
                                if ($a->file) { ?>
                                    <a href="<?= base_url().'assets/uploads/management_claim/'.$a->file ?>" class="btn btn-submit-black" target="_blank"><?= $a->file; ?></a>
                                <?php
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

<div class="row mb-5">
    <div class="col-lg-12">
        <a href="<?= base_url().'management_claim/log_aktivitas_export/'.$signature ?>" class="btn btn-submit-red" style="width: 100%">Export Data LOG</a>
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function () 
    {
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "ordering": false,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
