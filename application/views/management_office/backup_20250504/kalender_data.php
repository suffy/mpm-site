<style>
    .graphBox{
        position: relative;
        width: 100%;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 30px;
        min-height: 200px;
    }

    .containerChart{
        position: relative;
        width: 100%;
        padding: 20px;
        /* display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 30px; */
        min-height: 200px;
        /* background: var(--bs-dark-text-emphasis);
        color: var(--bs-body-bg); */
    }

    .graphBox .box{
        position: relative;
        /* background: #fff; */
        /* background-color: var(--bs-dark-text-emphasis); */
        background-color: var(---bs-body-bg);
        padding: 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
    }

    @media (max-width: 991px) {
        .graphBox{
            grid-template-columns: 1fr;
            height: auto;
        }
    }

</style>

<div class="containerChart">
    <div class="box">
        <table id="tabel" class="table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="4" class="text-center"> -- Kalender data (menuju closing april 2025) -- </th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>SubBranch</th>
                    <th>Tanggal Faktur</th>
                    <th>Last Upload</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($get_kalender_by_bulan->result() as $p) : ?>
                <tr>
                    <td><?= $p->branch_name ?></td>
                    <td><?= $p->nama_comp ?></td>
                    <td><?= $p->tanggal ?></td>
                    <!-- <td><?= date('d M y', strtotime($p->lastupload)); ?></td> -->
                    <td><?= $p->lastupload ? date('d M y', strtotime($p->lastupload)) : 'Belum Upload'; ?></td>
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