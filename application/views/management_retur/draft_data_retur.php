<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-5">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <table id="example" class="display" width="100%">
                    <thead>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">DP
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">No Ajuaan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Nota Retur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Tglbuat
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($get_data_retur->result() as $a) : ?>
                        <tr>
                            <td><?= $a->company; ?></td>
                            <td><?= $a->noajuan; ?></td>
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
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>