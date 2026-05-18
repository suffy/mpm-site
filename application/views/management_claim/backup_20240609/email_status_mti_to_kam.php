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
        background-color:darkslategray;
        color: #fff;
        border:1px solid darkslategray ;
        border-radius: 5px;
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

    <br>

    <table border="0">
        <tr>
            <td width="20%"><b> - Status</b></td>
            <td width="50%"><b>: <?= $nama_status; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Principal</b></td>
            <td width="50%">: <?= $namasupp; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Area</b></td>
            <td width="50%">: <?= $area; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Account</b></td>
            <td width="50%">: <?= $account; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Brand</b></td>
            <td width="50%">: <?= $brand; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Item</b></td>
            <td width="50%">: <?= $item; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Mekanisme</b></td>
            <td width="50%">: <?= $mekanisme; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Expose</b></td>
            <td width="50%">: <?= $expose; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Periode</b></td>
            <td width="50%">: <?= $from.' sd '.$to; ?></b></td>
        </tr>
        
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        
        <tr>
            <td width="20%">- Branch</b></td>
            <td width="50%">: <?= $branch_name; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Nomor Ajuan Claim</b></td>
            <td width="50%">: <?= $nomor_ajuan; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Nama Pengirim</b></td>
            <td width="50%">: <?= $nama_pengirim; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Email Pengirim</b></td>
            <td width="50%">: <?= $email_pengirim; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Tanggal Claim</b></td>
            <td width="50%">: <?= $tanggal_claim; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Attachment</b></td>
            <td width="50%">
                <?php 
                    if (!empty($attach_1)) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$attach_1; ?>"><button type="button" class="button_accept"><?= $attach_1 ?></button></a>
                    <?php
                    }
                ?>

                <?php 
                    if (!empty($attach_2)) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$attach_2; ?>"><button type="button" class="button_accept"><?= $attach_2 ?></button></a>
                    <?php
                    }
                ?>
            </td>
        </tr>

        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%">- Status Verifikasi</b></td>
            <td width="50%">: <?= $nama_status_verifikasi; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- PIC Verifikasi</b></td>
            <td width="50%">: <?= $name_verifikasi; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Keterangan Vwerifikasi</b></td>
            <td width="50%">: <?= $keterangan_verifikasi; ?></b></td>
        </tr>

        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%">- Status Hardcopy</b></td>
            <td width="50%">: <?= $nama_status_hardcopy_dp; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Nama Pengirim Hardcopy</b></td>
            <td width="50%">: <?= $nama_pengirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Email Pengirim Hardcopy</b></td>
            <td width="50%">: <?= $email_pengirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- Tanggal Pengirim Hardcopy</b></td>
            <td width="50%">: <?= $tanggal_kirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%">- File Hardcopy</b></td>
            <td width="50%">
                <?php 
                    if (!empty($file_hardcopy)) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$file_hardcopy; ?>"><button type="button" class="button_accept"><?= $file_hardcopy ?></button></a>
                    <?php
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
    </table>
    <br>

    

  </body>
</html>