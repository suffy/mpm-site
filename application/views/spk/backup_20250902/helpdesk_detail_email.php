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

    <p>Dear MPM</p>
    <p>Berikut adalah Pengajuan Helpdesk yang membutuhkan respon anda</p>
        
    <table border="0">
        <tr>
            <td width="20%"> - No Tiket</td>
            <td width="50%">: <?= $helpdesk->row()->no_tiket; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Branch | Site</td>
            <td width="50%">: <?= $helpdesk->row()->branch_name. ' - ' .$helpdesk->row()->nama_comp. ' ('.$helpdesk->row()->site_code.')'; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Principal</td>
            <td width="50%">: <?= $helpdesk->row()->namasupp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Surat Jalan</td>
            <td width="50%">: <?= $helpdesk->row()->surat_jalan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Expedisi</td>
            <td width="50%">: <?= $helpdesk->row()->ekspedisi; ?></td>
        </tr>
        <tr>
            <td width="20%"> - PIC</td>
            <td width="50%">: <?= $helpdesk->row()->pic; ?></td>
        </tr>
        <tr>
            <td width="20%"> - No. Telp</td>
            <td width="50%">: <?= $helpdesk->row()->telp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Email</td>
            <td width="50%">: <?= $helpdesk->row()->email; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Kategori</td>
            <td width="50%">: <?= $helpdesk->row()->nama_kategori; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Kronologis</td>
            <td width="50%">: <?= $helpdesk_detail->row()->pesan; ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">

            <a href='<?= base_url("spk/helpdesk_detail/$signature"); ?>'><button type="button" class="button_accept">Lihat Detail</button></a>

            </td>
        </tr>
    </table>
    <br> 

    <table>
        <tr>
            <th> History Pesan di Website</th>
        </tr>
    </table> 

    <table border = 1>
        <tr>
            <th width="10%">Nama</th>
            <th width="10%">Pesan</th>
            <th width="10%">Status</th>
            <th width="10%">Tanggal</th>
        </tr>
        <?php foreach ($helpdesk_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->username; ?></td>
            <td><?= $a->pesan; ?></td>
            <td><?= $a->nama_status; ?></td>
            <td><?= $a->created_at; ?></td>
        </tr>
        <?php endforeach; ?>

    </table> 

  </body>
</html>