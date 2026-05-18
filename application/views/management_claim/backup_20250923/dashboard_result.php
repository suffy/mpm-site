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
            <table id="tabel-data" class="display" style="display: inline-block; overflow-y: scroll; height:500px;">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Data Registrasi Program</th>
                        <th colspan="6" class="text-center">Data Pelaporan DP</th>
                    </tr>
                    <tr>
                        <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                            <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                        </th> 
                        <th width="10%">Nomor Surat</th>                          
                        <th width="10%">Kategori</th>                          
                        <th width="20%">Branch</th> 
                        <th width="15%">Nomor Ajuan</th>  
                        <th width="10%">Status</th>  
                        <th width="10%">Status Internal</th>  
                        <th>Tanggal Claim</th>
                        <th width="12%">Log</th>
                </thead>
                <tbody>     
                    <?php
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                            </center>
                            <input type="hidden" name="kategori[]" value="<?= $a->kategori; ?>">
                        </td> 
                        <td><?= $a->nomor_surat; ?></td> 
                        <td><?= $a->kategori; ?></td> 
                        <td><?= $a->nama_comp.' - '.$a->site_code.' - '.$a->id; ?></td>                          
                        <td><?= $a->nomor_ajuan; ?></td> 
                        <td><?= $a->nama_status; ?></td> 
                        <td><?= $a->nama_status_internal; ?></td> 
                        <td><?= $a->tanggal_claim; ?></td> 
                        <td>
                            <a href="<?= base_url().'management_claim/log_aktivitas/'.$a->signature; ?>" class="btn btn-submit-cream">Log</a>
                            <a href="<?= base_url().'management_claim/export_data/'.$a->signature; ?>" class="btn btn-submit-orange">Export</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
        <button type="button" class="btn btn-submit" onclick="convertTable()" style="width: 50%">Convert data diatas ke Format Excel</button>
        <input type="submit" value="Lanjut ke penarikan data pelaporan dp" class="btn btn-submit-red" id="btnKirim" onclick="return ValidateCompare()" style="width: 50%">
        <button class="btn btn-info" id="btnLoading" type="button" style="width: 50%" disabled>
        ... Sedang penarikan data. Mohon menunggu ...
        </button>
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function () 
    {
        $("#btnLoading").hide();
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "ordering": true,
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

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>

<script>
    function ValidateCompare() 
    {
        var c = document.getElementsByName("options[]");
        var count = 0;
        for (var i = 0; i < c.length; i++) 
        {
            if (c[i].checked) 
            {
                count++;
            }
        }
            if (count < 1) {
                alert("Anda belum memilih satu datapun.");
                return false;
            }else{
                $("#btnKirim").hide();
                $("#btnBack").hide();
                $("#btnLoading").show();
            }
    }
</script>