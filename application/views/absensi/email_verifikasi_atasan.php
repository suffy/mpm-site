<!doctype html>
<html lang="en">
  <head>
  
  <style type="text/css">

    .button_accept {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:green;
        color: #fff;
        border:none;
    }
    .button_reject {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:orangered;
        color: #fff;
        border:none;
    }

    table
    {
        border-collapse: collapse;
    }

    th, td 
    {
        padding: 5px;
        text-align: left;
    }


  </style>

  </head>
  <body>

    <p>Dear Bapak/Ibu <?= $atasan_karyawan ?></p>
    <p>Berikut adalah Absensi yang membutuhkan verifikasi anda</p>
        
    <table border="0">
        <tr>
            <td width="20%"> - Nama Karyawan</td>
            <td width="50%">: <?= $nama_karyawan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Periode Absensi</td>
            <td width="50%">: <?= $bulan_indo; ?> <?= $tahun ?></td>
        </tr>
        <tr>
            <td width="20%"> - Total Hari Kerja</td>
            <td width="50%">: <?= $total_hari_kerja; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Total Hadir</td>
            <td width="50%">: <?= $hadir; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Terlambat</td>
            <td width="50%">: <?= $terlambat; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanpa Keterangan</td>
            <td width="50%">: <?= $tidak_lengkap; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Created At</td>
            <td width="50%">: <?= $created_at; ?></td>
        </tr>    
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">
            <a href="<?= base_url() ?>absensi/verifikasi_atasan/<?= $signature ?>"><button type="button" class="button_accept">Approve / Reject</button></a>
            </td>
        </tr>
    </table>
    <br>


  </body>
</html>