</div>

<div class="container-fluid">
    <div class="row mt-1">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-12 mt-3">
            <form action="<?= $url ?>" method="GET">

                <div class="row mt-5">
                    <div class="col-md-2">
                        <label for="branch" class="form-label">Branch</label>
                    </div>
                    <div class="col-md-4">
                        <select name="branch" id="branch" class="form-control" required>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">
                        <label for="file_import" class="form-label">Tanggal Pengajuan Retur</label>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="from" value = "<?= $this->input->get('from') ?>" required>
                    </div>
                    To
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="to" value = "<?= $this->input->get('to') ?>" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-submit-black">Tampilkan Data</button>
                        <br><br>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr style="border: 1px solid black; box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);" class="mt-5">

    <div class="row mt-4 mb-5 d-flex justify-content-center gap-4">
        <div class="card" style="width: 20rem;">
            <div class="card-body">
                <h5 class="card-title text-center">Total Pengajuan Retur DP</h5>
                <p class="card-text text-center">
                    (<?= $company ?>)
                </p>
                <p class="card-text text-center">
                    <a href="<?= base_url().'management_retur/report_progress_nota_retur_export/'.$this->input->get('branch').'/'.$this->input->get('from').'/'.$this->input->get('to').'/all' ?>" class="btn btn-warning">&nbsp; <?= $total_data_all ?> &nbsp;</a>
                </p>
            </div>
        </div>
        <div class="card" style="width: 20rem;">
            <div class="card-body">
                <h5 class="card-title text-center">Total Nota Retur</h5>
                
                <p class="card-text text-center">
                    (<?= $company ?>)
                </p>
                <p class="card-text text-center">
                    <a href="<?= base_url().'management_retur/report_progress_nota_retur_export/'.$this->input->get('branch').'/'.$this->input->get('from').'/'.$this->input->get('to').'/done' ?>" class="btn btn-primary">&nbsp; <?= $total_data_done ?> &nbsp;</a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <table id="table-report">
                    <thead>
                        <tr>
                            <th style = "width: 100px;">Principal</th>
                            <th style = "width: 10px;">NoPengajuanRetur</th>
                            <th style = "width: 50px;">Status</th>
                            <th style = "width: 150px;">Branch</th>
                            <th style = "width: 80px;">Tanggal Pengajuan</th>
                            <th style = "width: 80px;">LPK / Tanda Terima</th>
                            <th style = "width: 100px;">Nota Retur MPM (Nodo_beli)</th>
                            <th style = "width: 80px;">CreatedAt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($get_data_all->result() as $a) : ?>
                        <tr>
                            <td><?= $a->namasupp; ?></td>
                            <td><?= $a->no_pengajuan; ?></td>
                            <td><?= $a->nama_status; ?></td>
                            <td><?= $a->nama_comp; ?></td>
                            <td><?= $a->tanggal_pengajuan; ?></td>
                            <td><?= $a->no_terima; ?></td>
                            <td><?= $a->nodo_beli; ?></td>
                            <td><?= $a->tglbuat; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () 
    {
        $('#table-report').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: "<?= base_url('management_retur/branch_dp'); ?>",
        data: '',
        success: function (hasil_branch) {
            $("select[name = branch]").html(hasil_branch);
        }
    });
</script>