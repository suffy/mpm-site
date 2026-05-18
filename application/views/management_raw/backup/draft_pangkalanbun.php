<style>

td {
  text-align: left;
  font-size: 12px;
}

th {
  text-align: left;
  font-size: 13px;
}

</style>

<?php echo form_open_multipart($url); ?>
<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>nomor#</th>
                            <th>tanggal</th>
                            <th>id_karyawan_tenaga</th>
                            <th>nama_tenaga_penjual</th>
                            <th>id_pelanggan</th>
                            <th>nama_pelanggan</th>
                            <th>kode#</th>
                            <th>nama_barang</th>
                            <th>@harga</th>
                            <th>kuantitas</th>
                            <th>penjualan</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_raw_draft->result() as $a) : ?>
                        <tr>
                            <td><?= $a->nomor; ?></td>
                            <td><?= $a->tanggal; ?></td>
                            <td><?= $a->id_karyawan_tenaga; ?></td>
                            <td><?= $a->nama_tenaga_penjual; ?></td>
                            <td><?= $a->id_pelanggan; ?></td>
                            <td><?= $a->nama_pelanggan; ?></td>
                            <td><?= $a->kode; ?></td>
                            <td><?= $a->nama_barang; ?></td>
                            <td><?= $a->harga; ?></td>
                            <td><?= $a->kuantitas; ?></td>
                            <td><?= $a->penjualan; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <center><h4>SUMMARY PRA-MAPPING</h4>    
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                Count Row / Jumlah Row Original
            </div>
            <div class="col-md-3">
                : <font size = "4px"><?= $get_summary->row()->count_raw ?> Row</font> 
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                Sum RP Kotor
            </div>
            <div class="col-md-3">
                : <font size = "4px">Rp. <?= number_format($get_summary->row()->raw_bruto) ?></font> 
            </div>
            <div class="col-md-3">
                Sum RP Netto
            </div>
            <div class="col-md-3">
                : <font size = "4px">Rp. <?= number_format($get_summary->row()->raw_netto) ?></font> 
            </div>
        </div>

        <hr>

        <center>
        <div class="row mt-5">
            <div class="col-md-12">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'lanjut proses mapping pangkalanbun', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
        </center>

    </div>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });
    });
</script>