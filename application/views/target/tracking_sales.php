<?= $this->load->view('target/component/sidebar');?>

<div class="card-block mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" class="datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tracking</th>
                        <th>Periode</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <a href="<?= base_url() ?>target_outlet/master_target/<?= $a->signature ?>" class="btn pending-scm"><?= $a->nama_tracking ?></a>
                        </td>
                        <td><?= $a->from.' - '.$a->to ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->username ?></td>
                        <td>
                            <a href="<?= base_url('target_outlet/delete_master_tracking/'.$a->signature) ?>" class="delete-button" onclick="return confirm('Hapus data ini ?')">del</a> 
                            <a href="<?= base_url('target_outlet/proses_tracking/'.$a->signature) ?>" class="send-email-button">Proses</a> 
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
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true
        });
    });
</script>

<script>
    function button()
    {
        let nama_tracking = document.getElementById('nama_tracking').value;
        let from = document.getElementById('from').value;
        let to = document.getElementById('to').value;

        if (nama_tracking && from && to) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>

