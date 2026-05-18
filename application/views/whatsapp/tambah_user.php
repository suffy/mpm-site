<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <form action="<?= base_url($url) ?>" method="post">

                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" class="form-control" name="nama" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="whatsapp" class="form-label">Whatsapp</label>
                        <input type="number" id="whatsapp" class="form-control" name="whatsapp" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-success btn-md">Simpan User</button>
                        <a href="<?= base_url('whatsapp/dashboard'); ?>" class="btn btn-dark btn-md">kembali</a>
                    </div>
                </div>

            </form>
        </div>

        <div class="row">
            <div class="col">

                <div class="dt-responsive table-responsive mt-4">
                    <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                    <table id="table-dc" class="table table-hover m-b-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Whatsapp</th>
                                <th>created at</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($get_user as $key) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $key->nama; ?></td>
                                    <td><?= $key->whatsapp; ?></td>
                                    <td><?= $key->created_at; ?></td>
                                    <td><a href="<?= base_url('whatsapp/delete_user/' . $key->signature); ?>" class="btn btn-danger btn-sm" onclick="return confirm('yakin mau dihapus ?')">delete</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


    </div>
</div>