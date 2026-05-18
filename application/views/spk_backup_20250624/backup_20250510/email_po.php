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

    <p><font size="6"><b>Purchase Order</b></font></p>

    <table border="0">
        <tr>
            <td width="10%">No</td>
            <td width="50%">:<strong> <?= $nopo.'</strong> - '.$tipe; ?></td>
        </tr>
        <tr>
            <td width="10%">Principal</td>
            <td width="50%">: <?= $namasupp; ?></td>
        </tr>
        <tr>
            <td width="10%">Branch</td>
            <td width="50%">: <?= $branch_name; ?></td>
        </tr>
        <tr>
            <td width="10%">SubBranch</td>
            <td width="50%">: <?= $nama_comp; ?></td>
        </tr>
        <tr>
            <td width="10%">Company</td>
            <td width="50%">: <?= $company; ?></td>
        </tr>
        <tr>
            <td width="10%">NPWP</td>
            <td width="50%">: <?= $npwp; ?></td>
        </tr>
        <tr>
            <td width="10%">Alamat Kirim</td>
            <td width="50%">: <?= ($alamat_kirim) ? $alamat_kirim. ' - '.$kode_alamat : $alamat; ?></td>
        </tr>
        <tr>
            <td width="10%">File PDF</td>
            <td width="50%">
               <a href="<?= base_url().'transaction/download_pdf/'.$id_po ?>"><button type="button" class="button_accept">download</button></a>
            </td>
        </tr>
    </table>
    <br><br>
    <table border = 1>
        <tr>
            <th width="10%">Kodeprod</th>
            <th width="10%">Prc</th>
            <th width="10%">Namaprod</th>
            <th width="10%">Unit</th>
            <th width="10%">Karton</th>
            <th width="10%">Berat</th>
            <th width="10%">Volume</th>
        </tr>
        <?php foreach ($get_po_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->kodeprod ?></td>
            <td><?= $a->kode_prc ?></td>
            <td><?= $a->namaprod ?></td>
            <td><?= $a->banyak ?></td>
            <td><?= $a->banyak_karton ?></td>
            <td><?= $a->berat ?></td>
            <td><?= $a->volume ?></td>
        </tr>
        <?php endforeach; ?>

    </table>  

  </body>
</html>