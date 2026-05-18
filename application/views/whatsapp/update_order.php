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
                        <label for="nama" class="form-label">Tanggal</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->tanggal; ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label for="nama" class="form-label">Nama | Whatsapp</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->nama . ' | ' . $get_order->whatsapp; ?>" readonly>
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="pesan" class="form-label">Pesan</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->pesan; ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label for="perkiraan_harga_makanan" class="form-label">Perkiraan Harga Makanan</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->perkiraan_harga_makanan; ?>" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="uang_masuk" class="form-label">Uang Masuk</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->uang_masuk; ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label for="uang_masuk" class="form-label">Saldo awal</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->saldo_awal; ?>" readonly>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="uang_masuk" class="form-label">Uang Sisa (saldo + uang masuk - uang keluar)</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->sisa; ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label for="uang_masuk" class="form-label">Saldo Akhir (sisa - dikembalikan)</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $get_order->saldo_akhir; ?>" readonly>
                    </div>
                </div>


                <div class="row mt-5">
                    <div class="col-md-5">
                        <label for="harga_makanan" class="form-label">Harga Makanan</label>
                        <input type="number" class="form-control" name="harga_makanan" value="<?= $get_order->harga_makanan; ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="uang_keluar" class="form-label">Uang Keluar</label>
                        <input type="number" class="form-control" name="uang_keluar" value="<?= $get_order->uang_keluar; ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="dikembalikan" class="form-label">Uang dikembalikan ke user</label>
                        <input type="number" class="form-control" name="dikembalikan" value="<?= $get_order->dikembalikan; ?>">
                    </div>
                </div>

                <input type="hidden" class="form-control" name="signature" value="<?= $get_order->signature; ?>" readonly>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">Update Orderan</button>
                        <a href="<?= base_url('whatsapp/dashboard'); ?>" class="btn btn-dark btn-md">kembali</a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('whatsapp/list_user') ?>',
            success: function(hasil_user) {
                $("select[name = nama]").html(hasil_user);
            }
        });
    })
</script>