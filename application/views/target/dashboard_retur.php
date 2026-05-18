<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>


    <div class="row">
        <div class="container">
            <div class="code-block" style="width: '100%';">
                <pre><strong>Informasi ! 
Data dibawah hanya menampilkan data 3 bulan terakhir</strong></pre>
            </div>
        </div>
    </div>
    

    <div class="row mt-5">
      <div class="col-md-12 mt-2">
        <button type="button" class="export-excel-btn" onclick="convertTable()">Export to Excel</button>
      </div>
    </div>


    <div class="row mt-5">
        <div class="col-md-12">
            <table id="tabel" class="datatable" >
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th width="10%">Principal</th>
                        <th width="10%">Subbranch</th>
                        <th width="10%">NoPengajuan</th>
                        <th width="10%">PrincipalHoAT</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->namasupp ?></td>
                        <td><?= $p->nama_comp.' - '.$p->site_code ?></td>
                        <td>
                            <a href="<?= base_url().'management_inventory/generate_pdf/'.$p->signature.'/'.$p->supp ?>" class="btn btn-submit-black" target="_blank"><?= ($p->no_pengajuan) ? $p->no_pengajuan : 'NULL'; ?></a>
                        </td>
                        <td><?= $p->principal_ho_at ?></td>
                        <td><?= $p->nama_status ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true,
            "fixedHeader": true,
            "scrollCollapse": true
        });
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>