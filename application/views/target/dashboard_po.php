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
                        <th width="10%">Principal</th>
                        <th width="10%">Subbranch</th>
                        <th width="10%">Company</th>
                        <th width="10%">Nopo</th>
                        <th width="10%">Tglpo</th>
                        <th width="10%">TotalValue</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $p->namasupp ?></td>
                        <td><?= $p->nama_comp ?></td>
                        <td><?= $p->company ?></td>
                        <td><a href="<?= base_url() . "transaction/download_pdf/" . $p->id ?>" target="_blank" class="btn btn-submit-black"><?= $p->nopo ?></a></td>
                        <td><?= $p->tglpo ?></td>
                        <td>Rp. <?= number_format($p->total_value) ?></td>
                        <td>
                            <?php
                                if ($p->status == '1') {                                
                                    $nama_status = "pending finance";
                                    $style = "font-size:14px";
                                    $class = "pending-finance";
                                } elseif ($p->status == '2') {
                                    if ($p->open == '1') {
                                        if ($p->nopo == null) {
                                            $nama_status = "pending rilis po";
                                            $style = "font-size:14px";
                                            $class = "pending-rilis-po";
                                        } else {
                                            $nama_status = "finish";
                                            $style = "font-size:14px";
                                            $class = "finish";
                                        }
                                    } else {
                                        $nama_status = "pending finance";
                                        $style = "font-size:14px";
                                        $class = "pending-finance";
                                    }
                                } else {
                                    $nama_status = "pending scm";
                                    $style = "font-size:14px";
                                    $class = "pending-scm";
                                }
                            ?>
                            <a href="<?= base_url() ?>spk/list_order_detail/<?= $p->signature ?>" class="btn btn-submit status <?= $class ?>" target="_blank" style = "<?= $style ?>"><?= $nama_status ?></a>

                        </td>
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
            "order": [0, 'desc'],
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