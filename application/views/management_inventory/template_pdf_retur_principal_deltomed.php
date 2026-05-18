<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MPM SITE | AJUAN RETUR</title>
  <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
  <style>
    .header {
      text-align: center;
      /* margin-bottom: 1rem; */
      font-weight: 700;
    }

    .table_bingkai_luar {
      width: 100%;
      /* border-collapse: collapse; */
      border: 1px solid;
    }

    .header-detail {
      margin-bottom: 1px;
      font-weight: 500;
      font-size: 12px;
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
      margin-top: 10px;
      margin-bottom: 10px;
      font-weight: 500;
      font-size: 14px;
      text-align: left;
    }

    .table-header,
    .th-header,
    .td-header {
      border-collapse: collapse;
      width: 100%;
      font-size: 14px;
    }

    .table-product,
    .th-product,
    .td-product {
      border-collapse: collapse;
      border: 1px solid;
      /* width: 100%; */
      padding: 8px;
    }

    .th-product {
      font-size: 13px;
      font-weight: 700;
      text-align: center;
    }

    .td-product {
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

    /* @page {
      margin: 100px 50px;
    } */

    #footer {
      position: fixed;
      left: 0px;
      bottom: -100px;
      right: 0px;
      height: 50px;
    }

    #footer .page:after {
      content: "Page " counter(page) " of <?= $no_pengajuan; ?>";
    }
  </style>
</head>

<body>
  <section id="about" class="about">
    <div class="header">
      <!-- <?= $no_pengajuan; ?><br> -->
      <h3 style="text-transform: uppercase;">FORM PENGEMBALIAN BARANG RETUR (FBR) - <?= $tipe; ?></h3>
    </div>

    <div class="row">
      <div class="content">
        <table style="align-items: center;" class="table-header">
          <tr>
            <td>Nama Distributor : <?= $company . ' / ' . $site_code; ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Category Produk yang diretur : <?= $namasupp; ?></td>
            <td></td>
          </tr>
          <tr>
            <td><br>Kepada Yth</td>
            <td><br>Tanggal FBR : <?= $tanggal_pengajuan; ?></td>
          </tr>
          <tr>
            <td>PT Deltomed Laboratories </td>
            <td>Nomor FBR : <?= $no_pengajuan; ?></td>
          </tr>
          <tr>
            <td>Cc PT Mulia Putra Mandiri</td>
            <td></td>
          </tr>
        </table>
      </div>

      <div class="content">
        <div class="header-last">
          Berikut ini kami mengajukan permohonan pengembalian barang (retur) dari cabang kami, dengan detail sbb :
        </div>
      </div>
    </div>

    <div class="row">
      <div class="content">

        <table class="table-product" style="width: 100%;">
          <tr>
            <th class="th-product" style="width: 10%;">Produk</th>
            <!-- <th class="th-product">Nama Produk</th> -->
            <th class="th-product" style="width: 10%;">No.Batch</th>
            <th class="th-product" style="width: 1%;">Unit/Sat</th>
            <th class="th-product" style="width: 5%;">Exp Date (bln/thn)</th>
            <th class="th-product" style="width: 5%;">QtyDp</th>
            <th class="th-product" style="width: 5%;">QtyArea</th>
            <th class="th-product" style="width: 5%;">QtyHo</th>
            <th class="th-product" style="width: 10%;">Nama Outlet (*)</th>
            <th class="th-product" style="width: 10%;">Alasan Retur (*)</th>
            <th class="th-product" style="width: 10%;">Keterangan Tambahan</th>
          </tr>
          <?php
          $no = 1;
          foreach ($get_pengajuan_detail->result() as $row) { ?>
            <tr>
              <td class="td-product"><?= $row->kodeprod.' - '.$row->namaprod; ?></td>
              <!-- <td class="td-product"><?= $row->namaprod; ?></td> -->
              <td class="td-product"><?= $row->batch_number; ?></td>
              <td class="td-product"><?= $row->satuan; ?></td>
              <!-- <td class="td-product"><?= $row->expired_date; ?></td> -->
              <td class="td-product"><?= date('m/Y', strtotime($row->expired_date)); ?></td>
              <td class="td-product"><?= $row->jumlah; ?></td>
              <td class="td-product"><?= $row->qty_approval; ?></td>
              <td class="td-product"><?= $row->qty_approval_ho; ?></td>
              <td class="td-product"><?= $row->nama_outlet; ?></td>
              <td class="td-product"><?= $row->alasan; ?></td>
              <td class="td-product"><?= $row->keterangan; ?></td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>

    <br>
    <div class="row">
      <table width="100%" border="0">
        <tr>
          <td width="30%">
            <font size="13px">
              (*) Wajib diisi. Nota retur atau Tanda terima pengembalian barang dari outlet / toko wajib dilampirkan
            </font>
          </td>
          <td>
            <center>
              <font size="13px">Diajukan oleh,<br><?= $tanggal_pengajuan ?>
            </center>
            </font>
          </td>
          <td>
            <center>
              <font size="13px">Disetujui oleh,<br><?= $principal_area_at ?>
            </center>
          </td>
          <td>
            <center>
              <font size="13px">Dicek oleh,<br><?= $verifikasi_at ?>
            </center>
          </td>
          <td>
            <center>
              <font size="13px">Disetujui oleh,<br><?= $principal_ho_at ?>
            </center>
          </td>
        </tr>
        <tr>
          <td>
            <font size="13px">
              (**) Alasan Retur : <br>
              A <span class="spasi">&nbsp;</span>Cacat Produksi (sesuai kriteria)<br>
              B <span class="spasi">&nbsp;</span>Kadaluarsa (sesuai ketentuan)<br>
              C <span class="spasi">&nbsp;</span>Produk Discontinue<br>
              D <span class="spasi">&nbsp;</span>Penarikan Pabrik<br>
              <!-- E <span class="spasi">&nbsp;</span>Lain-lain<br> -->
            </font>
          </td>
          <td>
            <center>
              <font size="13px">
                <?php
                if ($digital_signature) { ?>
                  <img src="<?= './assets/uploads/signature/' . $digital_signature ?>" alt="<?= $digital_signature ?>" width="150px">
                <?php
                } ?>
            </center>
          </td>
          <td>
            <center>
              <font size="13px">
              <?php 
                  $file = './assets/uploads/signature/'.$principal_area_signature;
                  $file2 = './assets_new/images/verified.png';
                  if($tipe == 'retur_khusus'){
                      echo "<img src='$file2' alt='approved' width='90px'>";
                  } else if ($principal_area_signature) {
                      echo "<img src='$file' alt='$principal_area_signature' width='150px'>";
                  }
              ?>
            </center>
          </td>
          <td style="vertical-align: bottom;">
            <center>
              <font size="13px">
                <?php 
                    $file = './assets/uploads/signature/ttd_p_fakhrul_stempel.jpg';
                    $file2 = './assets_new/images/verified.png';
                    if($tipe == 'retur_khusus'){
                        echo "<img src='$file' alt='approved' width='90px'>";
                    } else if ($verifikasi_signature) {
                        echo "<img src='$file' alt='ttd' width='90px'>";
                    }
                ?>
            </center>
          </td>
          <td>
            <center>
              <font size="13px">
                <?php
                $file = './assets/uploads/signature/' . $principal_ho_signature;
                if ($principal_ho_signature && $status != 10) { ?>
                  <img src="<?= $file ?>" alt="<?= $principal_ho_signature ?>" width="150px">
                <?php
                }
                ?>
            </center>
          </td>
        </tr>

        <tr>
          <td></td>
          <td>
            <center>
              <font size="13px"><?= $nama ?><br><b>DP
            </center>
          </td>
          <td>
            <center>
              <font size="13px"><?= $principal_area_username ?><br><b>Principal Area
            </center>
          </td>
          <td>
            <center>
              <font size="13px">Fakhrul Hidayat<br><b>PT MPM
          </td>
          <!-- <td><center><font size="13px"><?= $principal_ho_username ?><br><b>Deltomed Laboratories</b></center></td> -->
          <td>
            <center>
              <font size="13px"><?= $principal_ho_username ?><br><b>Principal HO</b>
            </center>
          </td>
        </tr>

        <tr>
          <td>
            <font size="13px">
              (***) Coret yang tidak sesuai
            </font>
          </td>
          <td colspan="3">
            <font size="13px"><br><b>Keputusan Deltomed atas ajuan Retur (Divisi S&M):</b><br>
              <?php
              if ($status == 5 || $status == 6 || $status == 7 || $status == 8 || $status == 9 || $status == 12) { ?>
                Disetujui / <strike>Ditolak</strike>
              <?php
              } elseif ($status == 10 || $status == 13) { ?>
                <strike>Disetujui</strike> / <font size="14"><strong>Ditolak</strong></font>
              <?php
              } else {
                echo "Disetujui / Ditolak";
              }
              ?>

              <br><br><b>Keputusan Deltomed atas Fisik Barang Retur :</b><br>
              <?php
              if ($status == 5 || $status == 6 || $status == 8) { ?>
                Fisik barang dikirim ke Pabrik Deltomed / <strike>Dilakukan Pemusnahan di lokasi outlet/DP</strike>
              <?php
              } elseif ($status == 7 || $status == 9 || $status == 12) { ?>
                <strike>Fisik barang dikirim ke Pabrik Deltomed</strike> / Dilakukan Pemusnahan di lokasi outlet/DP
              <?php
              } else {
                echo "Fisik barang dikirim ke Pabrik Deltomed / Dilakukan Pemusnahan di lokasi outlet/DP";
              }
              ?>
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