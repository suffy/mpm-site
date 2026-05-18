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

            <div class="title mt-4">
                <div class="row">
                    <div class="col text-left">
                        <a href="<?= base_url() ?>whatsapp/tambah_order" class="btn btn-primary btn-sm">tambah orderan</a>
                        <a href="<?= base_url() ?>whatsapp/tambah_user" class="btn btn-success btn-sm">tambah user</a>
                        <a href="<?= base_url() ?>dc/export_kartu_stock" target="_blank" class="btn btn-warning btn-sm">export excel</a>
                        <a href="<?= base_url() ?>dc/export_kartu_stock" target="_blank" class="btn btn-danger btn-sm">export pdf</a>
                    </div>
                </div>
            </div>

            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                <table id="table-dc" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <!-- <th>Whatsapp</th> -->
                            <th>Pesan</th>
                            <th>HargaMakanan</th>
                            <th>Awal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Sisa</th>
                            <th>Akhir</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_order as $key) : ?>
                            <tr>
                                <td><?= $key->tanggal; ?></td>
                                <td><?= $key->nama; ?></td>
                                <!-- <td><?= $key->whatsapp; ?></td> -->
                                <td><?= $key->pesan; ?></td>
                                <td><?= $key->harga_makanan; ?></td>
                                <td><?= $key->saldo_awal; ?></td>
                                <td><?= $key->uang_masuk; ?></td>
                                <td><?= $key->uang_keluar; ?></td>
                                <td><?= $key->sisa; ?></td>
                                <td><?= $key->saldo_akhir; ?></td>
                                <td><a href="<?= base_url('whatsapp/update_order/' . $key->signature); ?>" class="btn btn-primary btn-sm">update</a></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('dc/nodo_barang_keluar') ?>',
            success: function(hasil_kode) {
                $("select[name = kode_masuk]").html(hasil_kode);
            }
        });
    })
</script>