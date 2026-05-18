<style>
    .containerChart {
        border: 1px solid #e0e0e0;
        padding: 20px;
    }
</style>
<div class="containerChart">
    <div class="box">
        <table id="tabel" class="table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="5" class="text-center"> -- Kalender data (menuju closing Februari 2026) -- </th>
                </tr>
                <tr>
                    <th>SubBranch</th>
                    <th>Tanggal Sales</th>
                    <th>Tanggal Stok</th>
                    <th>Last Upload Sales</th>
                    <th>Status Closing</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($get_kalender_by_bulan as $p) : ?>
                <tr>
                    <td><?= $p->branch_name.' - '.$p->nama_comp ?></td>
                    <td><?= $p->tanggal ?></td>
                    <td><?= $p->tanggal_stok ? $p->tanggal_stok : 'Tidak ada data'; ?></td>
                    <td><?= $p->lastupload ? date('d M y', strtotime($p->lastupload)) : 'Belum Upload'; ?></td>
                    <td><?= $p->status_closing==1 ? 'Closing' : 'Belum Closing'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function () {
    $('#tabel').DataTable({
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