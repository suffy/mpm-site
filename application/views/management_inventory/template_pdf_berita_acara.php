<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MPM SITE | BERITA ACARA PEMUSNAHAN BARANG RETUR</title>
  <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
  <style>
    .header {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
    }

    .table_bingkai_luar {
      width: 100%;
      /* border-collapse: collapse; */
      border: 1px solid;
    }

    .header-detail {
      margin-bottom: 10px;
    }

    .header-container {
      display: flex;
    }

    .header-prolog {
      margin-bottom: 10px;
      font-weight: 500;
      font-size: 12px;
    }

    .header-prolog-no {
      font-weight: 500;
      font-size: 12px;
      text-align: right;
    }

    .header-last {
      margin-bottom: 10px;
      font-weight: 500;
      font-size: 12px;
      text-align: left;
    }

    .table-product,
    .th-product,
    .td-product {
      border-collapse: collapse;
      border: 1px solid;
      width: 100%;
    }

    .th-product {
      font-size: 13px;
      font-weight: 700;
      text-align: center;
    }

    .td-product {
      padding: 10px;
      font-size: 11px;
      /* padding: 8px; */
      text-align: left;
    }

    .table-footer,
    .th-footer,
    .td-footer {
      /* border-collapse: collapse; */
      border: none;
      width: 100%;
    }

    .td-footer {
      /* font-size: 13px; */
      /* padding: 8px; */
      text-align: center;
      vertical-align: top;
      width: auto;
    }

    @page { margin: 100px 50px; }
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; }
        #footer .page:after { content: "Page " counter(page) " of Berita Acara Pemusnahan <?= $no_pengajuan; ?>"; }
  </style>
</head>

<body>
  <section id="about" class="about">
    <div class="header">
      BERITA ACARA PEMUSNAHAN BARANG RETUR
    </div>

    <div class="row">
      <div class="content">
        <div class="header-detail">
          <table>
            <thead>
              <tr>
                <td>No</td>
                <td>: <?= $no_pemusnahan; ?></td>
              </tr>
              <tr>
                <td>Nama Distributor</td>
                <td>: <?= $company;?></td>
              </tr>
              <tr>
                <td>Sesuai FBR No</td>
                <td>: <?= $no_pengajuan; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><br></td>
              </tr>
              <tr>
                <td>Tanggal Pemusnahan</td>
                <td>: <?= $tanggal_pemusnahan; ?></td>
              </tr>
              <tr>
                <td>PIC Pemusnahan</td>
                <td style="text-transform: capitalize;">: <?= $nama_pemusnahan; ?></td>
              </tr>
              <tr>
                <td></td>
                <td>: ..............................</td>
              </tr>
              <tr>
                <td>Saksi Pemusnahan</td>
                <td>: ..............................</td>
              </tr>
              <tr>
                <td></td>
                <td>: ..............................</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <br>
    <div class="row">
      <div class="content">
        Detail barang yang dimusnahkan sebagai berikut:
        <table class="table-product">
          <tr>
            <th class="th-product">Kode Produk</th>
            <th class="th-product">Nama Produk</th>
            <th class="th-product">Batch</th>
            <th class="th-product">Satuan</th>
            <th class="th-product">Exp Date</th>
            <th class="th-product">Qty Approval</th>
            <th class="th-product">Qty Pemusnahan</th>
            <th class="th-product">Keterangan</th>
          </tr>
          <?php 
            $no = 1;
            foreach ($get_pengajuan_detail->result() as $row) { ?>
          <tr>
            <td class="td-product" style="text-align: center;"><?= $row->kodeprod; ?></td>
            <td class="td-product"><?= $row->namaprod; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->batch_number; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->satuan; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->expired_date; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->qty_approval_ho; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->qty_pemusnahan; ?></td>
            <td class="td-product" style="text-align: center;"><?= $row->keterangan_pemusnahan; ?></td>
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
            PIC Pemusnahan,
            <br><br><br><br><br>
            <p style="text-transform: capitalize;"><?= $nama_pemusnahan; ?></p>
          </td>
          <td width="20%">
            <br><br><br><br><br>
            ..............................
          </td>
          <td width="20%">
            Saksi Pemusnahan,
            <br><br><br><br><br>
            ..............................
          </td>
          <td width="20%">
            <br><br><br><br><br>
            ..............................
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