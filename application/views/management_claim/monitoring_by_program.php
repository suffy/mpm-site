<?php
$this->load->view('management_claim/monitoring_form');
?>

<div class="container-fluid mb-1 mt-5">

    <div class="row mt-5">
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

    <div class="card">
        <div class="card-body">


<?php 
    echo form_open($url); 
    $url      = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $validURL = str_replace("&", "&amp;", $url);
?>

<input type="hidden" name="url" value="<?= $validURL; ?>">

<div class="card-block mt-1 mb-1">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel-data" class="display" style="overflow-y: scroll; width: 100%">
                <thead>
                    <tr>
                        <th class="text-center"><input type="button" class="btn btn-submit btn-sm" id="toggle" value="click all" onclick="click_all_request()">
                        </th>
                        <th>Principal</th>                     
                        <th>Kategori</th>                     
                        <th>No.Surat</th>                     
                        <th>Nama Program</th>                     
                        <th>Periode</th>                     
                        <th>Bulan</th>                   
                        <th>TotalClaim</th>      
                        <th class="text-center">#</th>             
                    </tr>
                </thead>
                <tbody>     
                    <?php
                    foreach ($data->result() as $a) : ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->namasupp; ?></td>  
                        <td><?= $a->nama_kategori; ?></td>  
                        <td><?= $a->nomor_surat; ?></td>  
                        <td><?= $a->nama_program; ?></td>  
                        <td><?= $a->from.' sd '.$a->to; ?></td>  
                        <td><?=  (substr($a->from, 5, 2) == substr($a->to, 5, 2)) ? substr($a->from, 5, 2) : substr($a->from, 5, 2).' - '.substr($a->to, 5, 2)  ?></td>
                        <td><?= $a->count; ?></td>  
                        <td>
                            <a href="<?= base_url('management_claim/monitoring_by_program_detail/'.$a->signature); ?>" class="btn btn-submit">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>

<input type="submit" class="btn btn-submit-cream" style="width: 100%; height: 50px; margin-top: 10px" value="Search Data Based on Program">
<?php form_close(); ?>

<button type="button" class="btn btn-submit-black" onclick="convertTable()" style="width: 100%; height: 50px; margin-top: 10px">Convert data diatas ke Format Excel</button>


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