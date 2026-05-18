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

    <p>Dear Bapak/Ibu <?= $username_on_duty?></p>
    <p>Biaya Operasional Karyawan anda sudah diinput</p>
        
    <table border="0">
        <tr>
            <td width="20%"> - No Pengajuan</td>
            <td width="50%">: <?= $no_ajuan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Pelaksana</td>
            <td width="50%">: <?= $username; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Jabatan</td>
            <td width="50%">: <?= $jabatan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Periode BIOP</td>
            <td width="50%">: <?= $from. ' s/d ' .$to; ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">
            <a href="<?= base_url() ?>management_biop/dashboard_routing/<?= $signature?>"><button type="button"
                        class="button_accept">Cek Detail Biop</button></a>
            </td>
        </tr>
    </table>
    <br>
    <table border = 1>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Keterangan Tempat</th>
            <th>Biaya</th>
            <th>Biaya Adjustment Admin Claim</th>
        </tr>
        <?php foreach ($get_detail as $a) : ?>
        <tr>
            <td><?= $a->tanggal ?></td>
            <td><?= $a->nama_kategori ?></td>
            <td><?= $a->keterangan ?></td>
            <td><?= $a->keterangan_tempat ?></td>
            <td>Rp. <?= number_format($a->biaya) ?></td>
            <td>Rp. <?= number_format($a->biaya_admin_biop) ?></td>
            <!-- <td
                <?= ($a->status_claim == 1) ? 'Ya' : 'No' ?>
            </td> -->
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>