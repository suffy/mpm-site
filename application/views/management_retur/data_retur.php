<style>
    td {
        font-size: 11px;
    }

    th {
        font-size: 12px;
    }

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }
</style>

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
    <div class="col-md-12 mt-3">
        <form action="data_retur" method="post">
            <u>Pilih Periode</u>
            <div class="row mt-2">
                <div class="col-md-3">
                    <label for="branch" class="form-label">Branch</label>
                </div>
                <div class="col-md-4">
                    <select name="branch" id="branch" class="form-control">
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="file_import" class="form-label">Tanggal Pengajuan Retur</label>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="from" required>
                </div>
                To
                <div class="col-md-2">
                    <input type="date" class="form-control" name="to" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">

                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <br><br>
                    <?php if (date("Y-m-d H:i:s") > date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($created_at_sds)))) { ?>
                    Data sudah lawas klik link <a href="<?= base_url().'management_retur/update_data_retur'; ?>">Update
                        Data</a>, Untuk perbarui data.
                    <?php } ?>
                </div>
            </div>
        </form>
        <hr>
        BRANCH : <?php 
                    if ($userid == null) {
                        echo "ALL";
                    } else {
                        if ($get_data_retur->row() == null) {
                            echo '<div class="alert alert-danger" role="alert">
                            Belom Ada Pengajuan
                            </div>';
                        } else {
                            echo $get_data_retur->row()->nama_comp;
                        }
                    };
                ?>
        <br>
        PERIODE : <?php 
                    if ($from == null && $to == null) {
                        echo "-";
                    } else {
                        echo "$from - $to";
                    };
                ?>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 border border-dark rounded-10 shadow p-3 mt-3" style="margin: 10px;">
                <p>Pengajuan Retur</p>
                <h2 align="center">
                    <?php if ($get_data_retur->row() == null) {
                        $total_retur = 0;
                    } else {
                        $total_retur = $get_data_retur->row()->total_retur;
                    };?>
                    <a href="<?= base_url("management_retur/export_data_retur/1/$useridx/$from/$to"); ?>"><?= $total_retur; ?></a>
                </h2>
            </div>
            <div class="col-md-3 border border-dark rounded-10 shadow p-3 mt-3" style="margin: 10px;">
                <p>On Progress</p>
                <h2 align="center">
                    <?php if ($get_data_retur->row() == null) {
                        $total_onprogress = 0;
                    } else {
                        $total_onprogress = $get_data_retur->row()->total_progress - $get_data_retur->row()->total_finish;
                    };?>
                    <a href="<?= base_url("management_retur/export_data_retur/2/$useridx/$from/$to"); ?>"><?= $total_onprogress; ?></a>
                </h2>
            </div>
            <div class="col-md-3 border border-dark rounded-10 shadow p-3 mt-3" style="margin: 10px;">
                <p>Finish</p>
                <h2 align="center">
                    <?php if ($get_data_retur->row() == null) {
                    $total_finish = 0;
                } else {
                    $total_finish = $get_data_retur->row()->total_finish;
                };?>
                    <a href="<?= base_url("management_retur/export_data_retur/3/$useridx/$from/$to"); ?>"><?= $total_finish; ?></a>
                </h2>
            </div>
        </div>
        <br>
        <p>Note : <br>
            - Total pengajuan retur berdasarkan proses retur sampai pengiriman atau pemusnahan barang oleh
            principal. <br>
            - Total on progress berdasarkan retur yang sedang dikerjakan tim accounting. <br>
            - Total finish berdasarkan retur yang sudah diselasaikan tim accounting. <br>
            - Table data untuk kolom nota retur dan tglbuat akan ditampilkan jika proses retur sudah selasai.
        </p>
    </div>
</div>
<br>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <table id="example" class="display" width="100%">
                    <thead>
                        <tr>
                            <th style="background-color: darkslategray; color :white;" class="text-center" colspan="5">
                                Pengajuan Retur DP</th>
                            <th style="background-color: gray; color :white;" class="text-center" colspan="2">Nota
                                Retur MPM</th>
                        </tr>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">No
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">DP
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Tanggal Pengajuan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">No Ajuaan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">LPK
                            </th>
                            <th style="background-color: gray;" class="text-center">
                                <font color="white">Nota Retur
                            </th>
                            <th style="background-color: gray;" class="text-center">
                                <font color="white">Tglbuat
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($get_data_retur->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->nama_comp; ?></td>
                            <td><?= $a->tanggal_pengajuan; ?></td>
                            <td><?= $a->no_pengajuan; ?></td>
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
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: "<?= base_url('database_afiliasi/branch_dp'); ?>",
        data: '',
        success: function (hasil_branch) {
            $("select[name = branch]").html(hasil_branch);
        }
    });
</script>