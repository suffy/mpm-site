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

    <p><font size="6"><b>Monitoring Claim</b></font></p>

    <table border="0">
        <tr>
            <td width="10%">No Ajuan Claim</td>
            <td width="50%">:<strong> <?= $nomor_ajuan.'</strong>'; ?></td>
        </tr>
        <tr>
            <td width="10%">Principal</td>
            <td width="50%">:<strong> <?= $namasupp.'</strong>'; ?></td>
        </tr>
        <tr>
            <td width="10%">Status</td>
            <td width="50%">: <?= $status; ?></td>
        </tr>
        <tr>
            <td width="10%">Status Internal</td>
            <td width="50%">: <?= $status_internal.' => <span style="background-color: #e0e0e0; text-weight: bold; padding: 3px; color: #000;" >'.$username_on_duty; ?></span></td>
        </tr>

        <tr>
            <td width="10%">Keterangan PIC</td>
            <td width="50%">: <?= $log_keterangan; ?></td>
        </tr>
    </table>

    <hr>

    <table border="0">    
        <tr>
            <td width="10%">Nomor Surat</td>
            <td width="50%">: <?= $nomor_surat; ?></td>
        </tr>
        <tr>
            <td width="10%">Kategori</td>
            <td width="50%">: <?= $kategori; ?></td>
        </tr>
        <tr>
            <td width="10%">Nama Program</td>
            <td width="50%">: <?= $nama_program; ?></td>
        </tr>
        <tr>
            <td width="10%">Branch</td>
            <td width="50%">: <?= $branch_name.' - '.$nama_comp; ?></td>
        </tr>
        <tr>
            <td width="10%">Link to website</td>
            <td width="50%">
               <a href="<?= base_url().'management_claim/routing/'.$signature_program.'/'.$signature_ajuan ?>"><button type="button" class="button_accept">Go to website</button></a>
            </td>
        </tr>
    </table> 

    <hr>

    <table border="0">    
        <tr>
            <td width="10%">Nomor Resi</td>
            <td width="50%">: <?= $nomor_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="10%">Nama Pengirim Hardcopy</td>
            <td width="50%">: <?= $nama_pengirim_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="10%">Tanggal Kirim Hardcopy</td>
            <td width="50%">: <?= $tanggal_kirim_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="10%">Email Pengirim hardcopy</td>
            <td width="50%">: <?= $email_pengirim_hardcopy; ?></td>
        </tr>
        
    </table> 

    <br><br>
    <table border = 1>
        <tr>
            <th width="10%">Tanggal Log</th>
            <th width="10%">User => On Duty</th>
            <th width="10%">Keterangan</th>            
            <th width="10%">Status Internal</th>
            <th width="10%">File</th>
            <th width="10%">Zip</th>
        </tr>
        <?php foreach ($log->result() as $a) : ?>
        <tr>
            <td><?= date('d-m-Y', strtotime($a->created_at)) ?></td>
            <td><?= $a->username.' => '.$a->on_duty_username ?></td>
            <td><?= $a->keterangan ?></td>
            <td><?= $a->nama_status_internal ?></td>
            <td><?= $a->file ?></td>
            <td><?= $a->file_zip ?></td>
        </tr>
        <?php endforeach; ?>

    </table> 



  </body>
</html>