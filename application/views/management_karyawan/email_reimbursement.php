<!doctype html>

<html lang="en">
<head>

<style type="text/css">

    .button_detail {
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 12px;
        padding-right: 12px;
        background-color:#0d6efd;
        color: #fff !important;
        border:none;
        text-decoration:none;
        border-radius:4px;
        display:inline-block;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

</style>

</head>

<body>

```
<p>Dear Bapak/Ibu Nanita,</p>

<p>
    Berikut adalah pengajuan reimbursement karyawan yang membutuhkan verifikasi Anda:
</p>

<table border="0">
    <tr>
        <td>Nama Karyawan</td>
        <td>: <?= $nama_lengkap ?></td>
    </tr>
    <tr>
        <td>Tanggal Pengajuan </td>
        <td>: <?= $tanggal_pengajuan; ?></td>
    </tr>
    <tr>
        <td>Keterangan Reimbursement</td>
        <td>: <?= $keterangan ?></td>
    </tr>
    <tr>
        <td>Total Reimbursement</td>
        <td>: Rp <?= number_format($nominal); ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <br>
            <a href="<?= base_url() ?>management_karyawan/reimbursement" class="button_detail">
                Cek Detail Reimbursement
            </a>
        </td>
    </tr>
</table>

<br>

<!-- <table border="1">
    <thead>
        <tr>
            <th>Tanggal Nota</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
    </thead>

    <tbody>
        <?php 
            $grand_total = 0;
        ?>

        <?php foreach ($get as $a) : ?>

        <tr>
            <td><?= $a->tanggal_nota ?></td>
            <td><?= $a->nama_kategori ?></td>
            <td><?= $a->keterangan ?></td>
            <td>Rp <?= number_format($a->total) ?></td>
        </tr>

        <?php 
            $grand_total += $a->nominal;
        ?>

        <?php endforeach; ?>

    </tbody>

    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th>Rp <?= number_format($grand_total) ?></th>
        </tr>
    </tfoot>

</table> -->

<br>

<p>
    Mohon untuk dilakukan pengecekan dan proses approval lebih lanjut.
</p>

<p>
    Terima kasih.
</p>
```

</body>
</html>
