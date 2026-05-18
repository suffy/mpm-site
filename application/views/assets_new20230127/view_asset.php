<style>
    th {
        text-align: center;
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        white-space: normal !important;
    }
</style>

<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchModal">
        Tambah
    </button>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exportModal">
        Export
    </button>
</div>
<hr>
<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Group</th>
                <th>Tanggal Payroll</th>
                <th>Nilai Perolehan</th>
                <th>Status</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($asset as $a) : ?>
            <tr>
                <td><?= $a->kode; ?></td>
                <td><?= $a->namabarang; ?></td>
                <td><?= $a->namagrup; ?></td>
                <td><?= date('d F Y', strtotime($a->tglperol)); ?></td>
                <td>Rp. <?= number_format($a->np); ?></td>
                <td>
                    <?php 

                            if($a->nj==0||$a->nj=='')
                            {
                                $status ='Aktif';
                            }
                            else
                            {
                                $status ='Jual';
                            }

                            echo $status; 
                        ?></td>
                <td>
                    <a href="<?= base_url().'assets_new/detail_asset/'.$a->id; ?>" type="button"
                        class="btn btn-primary btn-sm">View</a>
                    <?php
                        echo anchor('assets_new/delete_asset/' . $a->id, 'delete',
                            array('class' => 'btn btn-danger btn-sm',
                                    'onclick'=>'return confirm(\'Are you sure?\')'));   
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
        <tfoot>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Group</th>
                <th>Tanggal Payroll</th>
                <th>Nilai Perolehan</th>
                <th>Status</th>
                <th>#</th>
            </tr>
        </tfoot>
    </table>
</div>
</body>

<!-- ---------------------------- modal search assets ------------------------------------ -->
<?php $this->load->view('assets_new/search_asset');?>

<!-- ---------------------------- modal export assets ------------------------------- -->
<?php $this->load->view('assets_new/export_asset');?>