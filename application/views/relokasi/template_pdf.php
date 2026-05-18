<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MPM SITE | Relokasi</title>
    <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
    <style>
    
      /* .header{
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 700;
      }

      .table_bingkai_luar{
        width: 100%;
        border: 1px solid;
      }

      .header-detail{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
      }

      .header-container{
        display: flex;
      }

      .header-prolog{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
      }

      .header-prolog-no{
        font-weight: 500;
        font-size: 12px;
        text-align: right;
      }

      .header-last{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
        text-align: left;
      } */

      /* .table-product, .th-product, .td-product{
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;
      } */

      .th-product{
        font-size: 13px;
        font-weight: 700;
        text-align: center;
      }

      .td-product{
        font-size: 12px;
        padding: 8px;
        text-align: left;
      }

      /* .table-footer, .th-footer, .td-footer{
        border: none;
        width: 100%;
      } */

      /* .td-footer{
        text-align: center;
        vertical-align: top;
        width: auto;
      } */

      /* table, th, td {
  border: 1px solid;
  border-collapse: collapse;
} */

.table-product{
    border: 1px solid;
  border-collapse: collapse;
}

    </style>
  </head>
  <body>
      <section id="about" class="about">
        <div class="header"><center>
            FORM PENGAJUAN RELOKASI</center>
        </div>

        <br>

        <div class="row">
            <div class="content">

                <!-- <div class="header-detail">
                    <b>No Relokasi : <?= $no_relokasi; ?><br>
                    <b>Category Produk yang diretur : <?= $category; ?><br>
                </div> -->
                
                <!-- <div class="header-container">

                    <div class="header-prolog">
                    <b>Kepada Yth <br>
                        PT Deltomed Laboratories <br>
                        Cc PT MPM
                    </div>
                    <div class="header-prolog-no">
                    <b>Nomor FBR : <?= $no_pengajuan; ?>
                    </div>
                    
                </div> -->

                <!-- <div class="header-last">
                <b>Berikut ini kami mengajukan permohonan pengembalian barang (retur) dari cabang kami, dengan detail sbb :
                </div> -->

                <table border="0" width="100%" class="tabel_header">
                    <tr>
                        <td width="10%">No Relokasi</td>
                        <td width="40%">: <?= $no_relokasi ?></td>
                    </tr>
                    <tr>
                        <td width="10%">Principal</td>
                        <td width="40%">: <?= $namasupp ?></td>
                    </tr>
                    <tr>
                        <td width="10%">Tanggal Pengajuan</td>
                        <td width="40%">: <?= $tanggal_pengajuan ?></td>
                    </tr>
                    <tr>
                        <td width="10%">PIC</td>
                        <td width="40%">: <?= $nama ?></td>
                    </tr>
                    <tr>
                        <td width="10%">From To</td>
                        <td width="40%">: <?= $from_nama_comp.' -> '.$to_nama_comp ?></td>
                    </tr>
                    <tr>
                        <td width="10%">Alasan</td>
                        <td width="40%">: <?= $alasan ?></td>
                    </tr>
                    <tr>
                        <td width="10%">Status</td>
                        <td width="40%">: 
                          <?php if ($status == 5 || $status == 6) { ?>
                            <font color="red"><?= $nama_status;
                          }else{ 
                            echo $nama_status;
                          } ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="11%">Supplychain Approval at</td>
                        <td width="40%">: <?= $approve_supplychain_at ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Finance Approval at</td>
                        <td width="40%">: <?= $approve_finance_at ?></td>
                    </tr>
                </table>

            </div>
        </div>

        <br>

        <div class="row">
          <div class="content">

            <table class="table-product" width="100%" border="1">
              <tr>
                <th width="20%" class="th-product">Kode Produk</th>
                <th class="th-product">Nama Produk</th>
                <th  width="20%" class="th-product">Qty</th>
              </tr>
            <?php 
            $no = 1;
            foreach ($detail->result() as $row) { ?>
              <tr>
                <td class="td-product"><?= $row->kodeprod; ?></td>
                <td class="td-product"><?= $row->namaprod; ?></td>
                <td class="td-product"><?= $row->qty; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>

        <br>
        <div class="row">
            <table width="100%" border="0">
                <tr>
                    <td width="30%"><center><font size="13px">Diajukan oleh,</center></font></td>
                    <td width="30%"><center><font size="13px">Supplychain Approval,</center></td>
                    <td width="30%"><center><font size="13px">Finance Approval,</center></td>
                </tr>
                <tr>
                    <td><center><font size="13px">
                    <?php 
                      if (file_exists("C:/xampp/htdocs/cisk/assets/uploads/signature/$username-signature.png")) { ?>
                        <img src="C:/xampp/htdocs/cisk/assets/uploads/signature/<?= $username ?>-signature.png" alt="ttd" width="90cm">    
                      
                      <?php }else{
                        echo '';
                      }
                    ?>
                    </center></td>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                        <?php 
                        
                        if (file_exists("C:/xampp/htdocs/cisk/assets/uploads/signature/$signature-signature.png")) { ?>
                            <img src="C:/xampp/htdocs/cisk/assets/uploads/signature/<?= $signature ?>-signature.png" alt="ttd" width="90cm">    
                          
                          <?php }else{
                            echo '';
                          }

                        ?>
                
                        </center></td>
                    <td><center><font size="13px">

                    <?php 
                        
                    if (file_exists("C:/xampp/htdocs/cisk/assets/uploads/signature/$signature-signature-finance.png")) { ?>
                        <img src="C:/xampp/htdocs/cisk/assets/uploads/signature/<?= $signature ?>-signature-finance.png" alt="ttd" width="90cm">    
                        
                        <?php }else{
                        echo '';
                        }

                    ?>


                    </center></td>
                </tr>
                <tr>
                    <td width="30%"><center><font size="13px"><?= $nama; ?></center></font></td>
                    <td width="30%"><center><font size="13px">Supplychain Head</center></td>
                    <td width="30%"><center><font size="13px">Finance Head</center></td>
                </tr>


            </table>
        </div>
        <br>
      </section>
    </body>
</html>