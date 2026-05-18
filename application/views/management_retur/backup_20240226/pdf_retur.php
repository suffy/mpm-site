<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>MPM Site</title>

    <style>
        .border {
            border-collapse: collapse;
            border-width: 1px;
            border-style: solid;
            border-color: black !important;
        }

        table {
            font-size: 10px;
        }
    </style>

</head>

<body>
    <table width="100%">
        <tr>
            <td class="border">
                <center>NOTA RETUR<br>Nomor : <?= $retur[0]->nodo;?></center>
            </td>
        </tr>
        <tr>
            <td class="border">
                <center>( Atas Faktur Pajak Nomor : <?= $retur[0]->noseri;?> Tanggal : <?= $retur[0]->tgldo;?> )
                </center>
            </td>
        </tr>

        <tr>
            <td class="border">
                <table width="100%">
                    <tr>
                        <td style="padding-left: 20px;" colspan="3"><b><u>Pembeli BKP</u></b></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">Nama</td>
                        <td width="2%">:</td>
                        <td width="57%">PT. MULIA PUTRA MANDIRI</td>
                        <td width="21%"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">NPWP</td>
                        <td width="2%">:</td>
                        <td width="57%">02.963.822.8-086.000</td>
                        <td width="21%"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">Alamat</td>
                        <td width="2%">:</td>
                        <td width="57%">
                            The Mahtala Building Lt.7, Jl. Alam Utama No.6, Panunggangan Pinang<br>
                            Kota Tangerang Banten - Indonesia
                        </td>
                        <td width="21%"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" colspan="3"><b><u>Kepada Penjual</u></b></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">Nama</td>
                        <td width="2%">:</td>
                        <td width="57%"><?= $retur[0]->company;?></td>
                        <td width="21%"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">NPWP</td>
                        <td width="2%">:</td>
                        <td width="57%"><?= $retur[0]->npwp;?></td>
                        <td width="21%"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;" width="20%">Alamat</td>
                        <td width="2%">:</td>
                        <td width="57%"><?= $retur[0]->alamat;?></td>
                        <td width="21%"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <th width="10%" class="border" style="text-align: center;">No. Urut</th>
            <th width="40%" class="border" style="text-align: center;">Macam dan Jenis BKP </th>
            <th width="10%" class="border" style="text-align: center;">Kuantum</th>
            <th width="20%" class="border" style="text-align: center;">Harga Satuan Menurut Faktur Pajak (Rp)</th>
            <th width="20%" class="border" style="text-align: center;">Harga Jual BKP (Rp)</th>
        </tr>
        <tr>
            <td style="vertical-align: top; height: 450px!important;" class="border">
                <table width="100%">
                    <?php 
                    $no = 1;
                    foreach($retur as $a): ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ;?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
            <td style="vertical-align: top; height: 450px!important;" class="border">
                <table width="100%">
                    <?php 
                    foreach($retur as $a): ?>
                    <tr>
                        <td style="text-align: left;"><?= $a->namaprod;?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
            <td style="vertical-align: top; height: 450px!important;" class="border">
                <table width="100%">
                    <?php 
                    foreach($retur as $a): ?>
                    <tr>
                        <td style="text-align: right;"><?= $a->banyak;?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
            <td style="vertical-align: top; height: 450px!important;" class="border">
                <table width="100%">
                    <?php 
                    foreach($retur as $a): ?>
                    <tr>
                        <td style="text-align: right;"><?= $a->harga;?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
            <td style="vertical-align: top; height: 450px!important;" class="border">
                <table width="100%">
                    <?php 
                    foreach($retur as $a): ?>
                    <tr>
                        <td style="text-align: right;"><?= $a->total;?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="border" style="text-align: left;">Jumlah Harga Jual BKP yang dikembalikan</td>
            <td class="border" style="text-align: right;"><?= $retur[0]->total2;?></td>
        </tr>
        <tr>
            <td colspan="4" class="border" style="text-align: left;">Dikurangi Potongan Harga
            </td>
            <td class="border" style="text-align: right;"><?= $retur[0]->potongan;?></td>
        </tr>
        <tr>
            <td colspan="4" class="border" style="text-align: left;">Jumlah BKP yang dikembalikan setelah dikurangi
                potongan harga</td>
            <td class="border" style="text-align: right;"><?= $retur[0]->bkp;?></td>
        </tr>
        <tr>
            <td colspan="4" class="border" style="text-align: left;">Ppn yang diminta kembali</td>
            <td class="border" style="text-align: right;"><?= $retur[0]->ppn;?></td>
        </tr>
        <tr>
            <td colspan="4" class="border" style="text-align: left;">Total Retur</td>
            <td class="border" style="text-align: right;"><?= $retur[0]->gt;?></td>
        </tr>
        <tr>
            <td colspan="5" class="border">
                <table width="100%">
                    <tr>
                        <td colspan="3" width="60%" style="text-align: left;"></td>
                        <td colspan="2" width="40%" style="text-align: center !important;">
                            <table width="100%">
                                <tr>
                                    <td>
                                        Tanggal,<?= $retur[0]->tgl_beli;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pembeli
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <U>EFRASIA SARA</U><br>
                                        AKUNTING
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left!important;">
                Lembar ke-1 : untuk PKP Penjual<br>
                Lembar ke-2 : untuk Pembeli
            </td>
            <td style="text-align: right!important;">
                Hal : 1 dari 1
            </td>
        </tr>
    </table>
</body>

</html>