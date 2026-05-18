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
                        <input type="date" class="form-control" name="tanggal">
                    </div>
                    <div class="col-md-5">
                        <label for="nama" class="form-label">Nama</label>
                        <select name="nama" class="form-control" required>
                        </select>
                    </div>
                </div>


                <div class="row mt-5">
                    <div class="col-md-5">
                        <label for="pesan" class="form-label">Pesan</label>
                        <input type="text" id="pesan" class="form-control" name="pesan" required>
                    </div>
                    <div class="col-md-5">
                        <label for="perkiraan_harga_makanan" class="form-label">Perkiraan Harga Makanan</label>
                        <input type="number" id="perkiraan_harga_makanan" class="form-control" name="perkiraan_harga_makanan" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-5">
                        <label for="uang_masuk" class="form-label">Uang Masuk</label>
                        <input type="number" id="uang_masuk" class="form-control" name="uang_masuk" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">Simpan Orderan</button>
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