<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<?php $this->load->view('management_claim/css/style') ?>

</div>
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

<?php echo form_open($url); ?>
<div class="card-block mt-5 mb-1">
    <div class="row">
        <div class="col-md-12">
            <!-- <table id="tabel-data" class="display" style="overflow-y: scroll; width: 100%"> -->
            <!-- <table id="tabel-data"> -->
                <table id="tabel-ajuan-claim">
                <thead>
                    <tr>
                        <th class="text-center"><input type="button" class="btn btn-submit btn-sm" id="toggle" value="click all" onclick="click_all_request()">
                        </th>
                        <th>NomorSurat</th>          
                        <th>NamaProgram</th>          
                        <th>Subbranch</th>          
                        <th>NomorAjuan</th>   
                        <th>Tanggal</th>       
                        <th>Status</th>          
                        <th>StatusDetail</th>    
                        <th>FileExcel</th>      
                        <th>FileZip</th>      
                        <th>FileRaw</th>      
                        <th>DeletedAt</th>      
                    </tr>
                </thead>
                <tbody>     
                    <?php
                    foreach ($data->result() as $a) : ?>
                    <?php 
                        // echo "a->tahun_folder : ".$a->tahun_folder;
                        if ($a->tahun_folder == 2024) {
                            $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                        }else{
                            $url = base_url()."assets/uploads/management_claim/2025/";
                        }
                    ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id_import_header; ?>">
                            </center>
                        </td>
                        <td><?= $a->nomor_surat; ?></td>  
                        <td><?= $a->nama_program; ?></td>  
                        <td><?= $a->nama_comp; ?></td>  
                        <td><?= $a->nomor_ajuan; ?></td>  
                        <td><?= $a->tanggal_claim; ?></td>
                        <td><?= $this->model_management_claim->get_status($a->status)->row()->nama_status; ?></td>  
                        <td><?= $this->model_management_claim->get_status_internal($a->status_internal)->row()->nama_status; ?></td>  
                        <td>
                            <?php 
                                if($a->ajuan_excel){ ?>
                                    <a href="<?= $url.$a->kategori.'/'.$a->ajuan_excel ?>" class='btn btn-submit-cream'>click here</a>
                                <?php
                                }
                            ?>                            
                        </td>
                        <td>
                            <?php 
                                if ($a->ajuan_zip) { ?>
                                    <a href="<?= $url.$a->kategori.'/'.$a->ajuan_zip ?>" class='btn btn-submit-cream'>click here</a>
                                <?php
                                }
                            ?>                            
                        </td>
                        <td>
                            <?php 
                                if ($a->id_import_header && $id_kategori == 2) { ?>
                                    <a href="<?= base_url().'management_claim/export_raw_bonus_barang/'.$a->id_import_header; ?>" class="btn btn-submit-orange">export</a>
                                <?php
                                }elseif($a->id_import_header && $id_kategori == 5)
                                { ?>
                                    <a href="<?= base_url().'management_claim/export_raw_diskon/'.$a->id_import_header; ?>" class="btn btn-submit-orange">export</a>
                                <?php
                                }
                            ?>                            
                        </td>
                        <td><?= $a->deleted_at; ?></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>

<input type="hidden" name="id_kategori" value="<?= $id_kategori; ?>">

<input type="submit" class="btn btn-submit-cream" style="width: 100%; height: 50px; margin-top: 10px" value="Export Raw Data">
<?php form_close(); ?>

<!-- <button type="submit" class="btn btn-submit-cream" style="width: 100%; height: 50px; margin-top: 10px">Export Raw Data</button> -->
<button type="button" class="btn btn-submit-black" onclick="convertTable()" style="width: 100%; height: 50px; margin-top: 10px">Convert data diatas ke Format Excel</button>
                    </div>
                    </div>
                    </div>

<script>
    $(document).ready(function () 
    {
        $('#tabel-ajuan-claim').DataTable({
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