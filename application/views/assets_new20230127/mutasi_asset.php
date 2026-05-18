<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        text-transform: Capitalize;
        white-space: normal !important;
    }
</style>

<a href="<?= base_url('assets_new/penyerahan_asset');?>" class="btn btn-sm btn-dark">Kembali</a>

<form action="<?= base_url($url); ?>" method="post">
    <div class="modal-body">
        <div class="form-group row">
            <label for="no_po" class="col-sm-4 col-form-label">No. POF</label>
            <div class="col-sm-8">
                <input list="data_no_po" name="no_po" id="input_no_po" class="form-control" value="<?= $no_po;?>"
                    readonly>
            </div>
        </div>
        <!-- <div class="form-group row">
            <label for="no_pr" class="col-sm-4 col-form-label">No. PR</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="no_pr" id="no_pr" readonly>
            </div>
        </div> -->
        <div class="form-group row">
            <label for="tanggal" class="col-sm-4 col-form-label">Tanggal Penyerahan</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" name="tanggal" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="ekspedisi" class="col-sm-4 col-form-label">Ekspedisi</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="ekspedisi" id="ekspedisi">
            </div>
        </div>
        <div class="form-group row">
            <label for="resi" class="col-sm-4 col-form-label">Resi</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="resi" id="resi">
            </div>
        </div>
        <div class="form-group row">
            <label for="penerima" class="col-sm-4 col-form-label">Nama Penerima</label>
            <div class="col-sm-8">
                <select name="penerima" id="penerima" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <?php foreach($user as $value){?>
                    <option value="<?= $value->id;?>"><?= $value->username;?> | <?= $value->email;?>
                    </option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="harga" class="col-sm-4 col-form-label">Ongkir</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" name="harga" id="harga">
            </div>
        </div>
        <div class="form-group row">
            <label for="status" class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8">
                <select class="form-control" name="status" id="status" required>
                    <option value="">- Pilih -</option>
                    <option value="baru">Baru</option>
                    <option value="mutasi">Mutasi</option>
                </select>
            </div>
        </div>
    </div>
    <div align="center">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>

<hr>

<h5><u>History Mutasi</u></h5>
<br>
<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>
                    User
                </th>
                <th>
                    Tanggal
                </th>
                <th>
                    Status
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $a) : ?>
            <tr>
                <td>
                    <?= $a->username; ?>
                </td>
                <td>
                    <?= date('d F Y', strtotime($a->tgl_pengiriman)); ?>
                </td>
                <td>
                    <?= $a->status; ?>
                </td>
                <td>
                    <?php if ($a->flag == 1) {
                        echo "Aktif";
                    } else {
                        echo "Tidak Aktif";
                    } ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>