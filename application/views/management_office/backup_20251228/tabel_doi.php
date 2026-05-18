

<div class="containerChart">
    <div class="box">
        <table id="tabel_doi" class="table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>SubBranch</th>
                    <th>Principal</th>
                    <th>Kodeprod</th>
                    <th>Namaprod</th>
                    <th>AVG</th>
                    <th>Stock</th>
                    <th>DOI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($get_doi as $p) : ?>
                <tr>
                    <td><?= $p->nama_comp.' - '.$p->branch_name ?></td>
                    <td><?= $p->namasupp ?></td>
                    <td><?= $p->kodeprod ?></td>
                    <td><?= $p->namaprod ?></td>
                    <td><?= $p->avg_unit; ?></td>
                    <td><?= $p->stock_akhir; ?></td>
                    <td><?= $p->doi_unit; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function () {
    $('#tabel_doi').DataTable({
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