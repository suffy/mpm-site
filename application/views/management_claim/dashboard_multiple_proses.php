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
                        <th></th>
                        <th colspan="6" class="text-center">Registered Program</th>
                        <th colspan="2" class="text-center">Count DP</th>
                    </tr>
                    <tr>
                        <th width="1%" class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                            <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                        </th>
                        <th width="10%">Principal</th>                     
                        <th width="10%">Kategori</th>                     
                        <th width="10%">No.Surat</th>                     
                        <th>Nama Program</th>                     
                        <th width="5%">CreatedBy</th>                     
                        <th width="5%">CreatedAt</th>                     
                        <th width="5%">Eligible</th>                     
                        <th width="5%">Claimed</th>                     
                    </tr>
                </thead>
                <tbody>     
                    <?php
                    foreach ($get_data->result() as $a) : ?>
                    
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                            </center>
                        </td> 
                        <td><?= $a->namasupp; ?></td>  
                        <td><?= $a->kategori; ?></td>  
                        <td><?= $a->nomor_surat; ?></td>  
                        <td><?= $a->nama_program; ?></td>  
                        <td><?= $a->username; ?></td>  
                        <td><?= date("Y-m-d", strtotime($a->created_at)); ?></td>  
                        <td class="text-center"><?= $a->count_dp_eligible; ?></td>  
                        <td class="text-center"><?= $a->count_dp_claimed; ?></td>  
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
        <input type="submit" value="Lanjut Pencarian Data" class="btn btn-submit-red" style="width: 50%" onclick="return ValidateCompare()">
    </div>
</div>

<?= form_close(); ?>


<script>
    $(document).ready(function () 
    {
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