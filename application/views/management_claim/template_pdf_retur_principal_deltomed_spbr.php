<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MPM SITE | SURAT PERSETUJUAN BARANG RETUR</title>
  <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
  <style>
    
      .header{
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 700;
      }

      .table_bingkai_luar{
        width: 100%;
        /* border-collapse: collapse; */
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
      }

      .table-product, .th-product, .td-product{
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;
      }

      .th-product{
        font-size: 13px;
        font-weight: 700;
        text-align: center;
      }

      .td-product{
        font-size: 11px;
        /* padding: 8px; */
        text-align: left;
      }

      .table-footer, .th-footer, .td-footer{
        /* border-collapse: collapse; */
        border: none;
        width: 100%;
      }

      .td-footer{
        /* font-size: 13px; */
        /* padding: 8px; */
        text-align: center;
        vertical-align: top;
        width: auto;
      }
      
      @page { margin: 100px 50px; }
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; }
        #footer .page:after { content: "Page " counter(page) " of <?= $no_pengajuan; ?>"; }
    </style>
</head>

<body>
  <section id="about" class="about">
    <div class="header">
      SURAT PERSETUJUAN BARANG RETUR (SPBR)
    </div>

    <div class="row">
      <div class="content">
        <div class="header-detail">
          <pre>
Tanggal : <?= $tanggal_terima_barang;?><br>
No      : <?= $no_terima_barang; ?><br>
Hal     : Surat Persetujuan Barang Retur
          </pre>
        </div>
        <div class="header-container">
          <div class="header-prolog">
            Kepada Yth :<br>
            <b>PT Mulia Putra Mandiri</b><br>
            Cc : <br>
            <b><?= $company.' / '.$site_code; ?></b>
          </div>
        </div>
        <div class="header-last">
          Bersama ini kami menyampaikan Surat Persetujuan Barang Retur, sesuai dengan FBR No : <br>
          <?= $no_pengajuan; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="content">

        <table class="table-product">
          <tr>
            <th class="th-product">Kode Produk Deltomed</th>
            <th class="th-product">Nama Produk</th>
            <th class="th-product">No. Batch</th>
            <th class="th-product">Unit/Sat</th>
            <th class="th-product">Exp Date (bln/thn)</th>
            <th class="th-product">Qty</th>
            <th class="th-product">Keterangan Tambahan</th>
          </tr>
          <?php 
            $no = 1;
            foreach ($get_pengajuan_detail->result() as $row) { ?>
          <tr>
            <td class="td-product"><?= $row->kodeprod; ?></td>
            <td class="td-product"><?= $row->namaprod; ?></td>
            <td class="td-product"><?= $row->batch_number; ?></td>
            <td class="td-product"><?= $row->satuan; ?></td>
            <td class="td-product"><?= $row->expired_date; ?></td>
            <td class="td-product"><?= $row->qty_lpk; ?></td>
            <td class="td-product"><?= $row->keterangan_terima_barang; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>

    <br>
    <div class="row">
      <table width="100%" border="0">
        <tr>
          <td width="20%">
            Demikian kami sampaikan, harap ditindaklanjuti dengan Nota Retur sesuai dengan prosedur
            yang berlaku.
            <br><br>
            Hormat Kami
            <br><br><br><br><br>
            Etie Widjajanti
            <br>
            Cc : Accounting, Arsip
          </td>
        </tr>
      </table>
    </div>
    <br>
  </section>

  <div id="footer">
    <p style="text-align: right;" class="page"></p>
  </div>
</body>

</html>