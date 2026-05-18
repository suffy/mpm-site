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
            <td><b>Detail Pengajuan Claim</b></td>
        </tr>
        <tr>
            <td width="20%">No Pengajuan</td>
            <td width="50%">: <?= $nomor_ajuan; ?></td>
        </tr>
        <tr>
            <td width="20%">- Status</td>
            <td width="50%">: <?= $nama_status; ?></td>
        </tr>
        <tr>
            <td width="20%">- Status Internal</td>
            <td width="50%">: <?= $nama_status_internal; ?></td>
        </tr>
        <tr>
            <td width="20%">- Keterangan</td>
            <td width="50%">: <?= $keterangan; ?></td>
        </tr>
        
        <!-- <tr>
            <td colspan="2"><hr></td>
        </tr> -->
        <br>
        <tr>
            <td><b>Data Program</b></td>
        </tr>
        <tr>
            <td width="20%"> - Principal</td>
            <td width="50%">: <?= $namasupp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nomor surat</td>
            <td width="50%">: <?= $nomor_surat; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama program</td>
            <td width="50%">: <?= $nama_program; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Kategori</td>
            <td width="50%">: <?= $kategori; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Periode</td>
            <td width="50%">: <?= $periode; ?></td>
        </tr>
        <!-- <tr>
            <td width="20%"> - File PDF</td>
            <td width="50%">
                <?php 
                    if ($upload_pdf) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_pdf ?>"><button type="button" class="button_accept"><?= $upload_pdf ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%"> - File JPG</td>
            <td width="50%">
                <?php 
                    if ($upload_jpg) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_jpg ?>"><button type="button" class="button_accept"><?= $upload_jpg ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr> -->
        <!-- <tr>
            <td colspan="2"><hr></td>
        </tr> -->
        <br>
        <tr>
            <td><b>Data Pelaporan Claim</b></td>
        </tr>
        <tr>
            <td width="20%"> - Branch</td>
            <td width="50%">: <?= $branch_name.' | '.$nama_comp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Pengirim</td>
            <td width="50%">: <?= $nama_pengirim; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Email Pengirim</td>
            <td width="50%">: <?= $email_pengirim; ?></td>
        </tr>
        <!-- <tr>
            <td width="20%"> - Ajuan Excel</td>
            <td width="50%">
                <?php 
                    if ($params_folder == 'import') { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_excel ?>"><button type="button" class="button_accept"><?= $ajuan_excel ?></button></a>
                    <?php
                    }else{ ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$ajuan_excel ?>"><button type="button" class="button_accept"><?= $ajuan_excel ?></button></a>
                    <?php
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%"> - Ajuan Zip</td>
            <td width="50%">
                <?php 
                    if ($params_folder == 'import') { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_zip ?>"><button type="button" class="button_accept"><?= $ajuan_zip ?></button></a>
                    <?php
                    }else{ ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$ajuan_zip ?>"><button type="button" class="button_accept"><?= $ajuan_zip ?></button></a>
                    <?php
                    }
                ?>
            </td>
        </tr> -->
        <tr>
            <td width="20%"> - Tanggal Claim at</td>
            <td width="50%">: <?= $tanggal_claim; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Created at</td>
            <td width="50%">: <?= $created_at; ?></b></td>
        </tr>
        <br>
        <tr>
            <td><b>Data Pengiriman Hardcopy</b></td>
        </tr>
        <tr>
            <td width="20%"> - Nomor Resi</td>
            <td width="50%">: <?= $nomor_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Kirim</td>
            <td width="50%">: <?= $tanggal_kirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Pengirim</td>
            <td width="50%">: <?= $nama_pengirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Email Pengirim</td>
            <td width="50%">: <?= $email_pengirim_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Created At</td>
            <td width="50%">: <?= $update_kirim_hardcopy_at; ?></b></td>
        </tr>
        <br>
        <tr>
            <td><b>Data Penerimaan Hardcopy</b></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Terima di MPM</td>
            <td width="50%">: <?= $tanggal_terima_hardcopy; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Created At</td>
            <td width="50%">: <?= $update_terima_hardcopy_at; ?></b></td>
        </tr>
        <br>
        <tr>
            <td colspan="2"><b>Data Penyerahan Hardcopy ke Principal</b></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Penyerahan</td>
            <td width="50%">: <?= $tanggal_tanda_terima_hardcopy_ke_principal; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - PIC Principal</td>
            <td width="50%">: <?= $tanda_terima_hardcopy_ke_principal_nama; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Created At</td>
            <td width="50%">: <?= $update_tanda_terima_hardcopy_ke_principal; ?></b></td>
        </tr>

    </table>
  </body>
</html>