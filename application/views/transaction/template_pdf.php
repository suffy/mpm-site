<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap-4.6.2-dist/css/bootstrap.min.css'); ?>">
    <title>MPM | Pdf_PO_<?= $header->nopo; ?></title>
    <style>
        .img {
            position: absolute;
            top: 0px;
            left: 0px;
        }

        h1 {
            font-family: "Times New Roman", Times, serif;
            font-size: 30px;
        }

        .header {
            font-size: 12px;
            font-family: "Times New Roman", Times, serif;
            vertical-align: top;
            /* padding: 5px; */
            padding-top: 5px;
            font-weight: bold;
        }

        .subheader {
            font-size: 12px;
            font-family: "Times New Roman", Times, serif;
            vertical-align: top;
            padding-top: 5px;
            font-weight: normal;
        }

        th {
            font-size: 12px;
            text-align: center;
        }

        td {
            font-size: 10px;
        }

        table th,
        table td {
            /* white-space: normal !important; */
        }
    </style>
</head>

<body>
    <img src="./assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center><br>
        <h1 style="font-size: 20px; margin-top: 10px; font-weight: bold;">SURAT PESANAN BARANG</h1>
    </center>
    <br>
    <div class="row">
        <div class="col">
            <table class="header" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <td class="header" width="100px">Pemesan</td>
                        <!-- <td class="header" width="2px">:</td> -->
                        <td class="header" width="280px">: <b>PT. MULIA PUTRA MANDIRI</b></td>
                        <td class="header" width="70px" style="padding-left: 15px;">Tgl. Dok</td>
                        <!-- <td class="header" width="2px">:</td> -->
                        <td class="subheader">: <?= $header->tglpo; ?></td>
                    </tr>
                    <tr>
                        <td class="header">Di Kirim Kepada</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->company; ?></td>
                        <td class="header" style="padding-left: 15px;">No. Dok</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->nopo; ?></td>
                    </tr>
                    <tr>
                        <td class="header">NPWP</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->npwp; ?></td>
                        <td class="header" style="padding-left: 15px;">Tipe. Dok</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->tipe; ?></td>
                    </tr>
                    <tr>
                        <td class="header">Alamat Dp</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->alamat; ?></td>
                        <td class="header" style="padding-left: 15px;">PO Ref</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: <?= $header->po_ref; ?></td>
                    </tr>
                    <tr>
                        <td class="header">Alamat Kirim</td>
                        <!-- <td class="header">:</td> -->
                        <td class="subheader">: 
                            <?php if ($header->alamat_kirim == '') {
                                echo '<i>Sama dengan alamat diatas</i>';
                            } else {
                                echo $header->alamat_kirim;
                            }; ?>
                        </td>
                        <td class="header" style="padding-left: 15px;">Note</td>
                        <td class="subheader">: <?= $header->note; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <br>

    <div class="row">
        <div class="col">
            <table class="table table-bordered" style="height: 10px;">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Kode Prc</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Karton</th>
                        <th>Berat (Kg)</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($detail as $key) { ?>
                        <tr>
                            <td><?= $key->kodeprod; ?></td>
                            <td><?= $key->kode_prc; ?></td>
                            <td><?= $key->namaprod; ?></td>
                            <td><?= $key->banyak; ?></td>
                            <td><?= $key->banyak_karton; ?></td>
                            <td><?= $key->sub_berat; ?></td>
                            <td><?= $key->sub_volume; ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php 
                            if ($header->nopo) { ?>
                                <th colspan="7" rowspan="2" align="right">
                                Jakarta, <?= $header->tglpo; ?><br>
                                Penanggung Jawab <br>
                                <img src="./assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="ttd" width="90cm"><br>
                                Fakhrul Hidayat
                                </th>
                            <?php
                            }else{ ?><br>
                                <th colspan="7" rowspan="2" align="right"> <i>Belum Rilis</i> </th>
                            <?php
                            }
                        ?>
                        
                    </tr>
                    <tr>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    
</body>

</html>