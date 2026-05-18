<!doctype html>
<html>

<head>
    <title><?= ucwords($no_pr); ?></title>
</head>
<style>
    img.logo {
        position: absolute;
        top: 0px;
        left: 0px;
    }

    img.verified {
        position: absolute;
    }

    h1 {
        font-family: "Times New Roman", Times, serif;
    }

    th {
        text-align: center;
    }

    th,
    td {
        padding: 4px;
        font-size: 12px;
    }

    table th,
    table td {
        white-space: normal !important;
    }

    /*
    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        width: 50%;
        font-size: 10px;
        padding: 4px;
    }

    table th,
    table td {
        overflow: hidden !important;
        white-space: normal !important;
    } */
</style>

<body>
    <img src='<?= base_url('assets/css/images/mpm_new.jpg');?>' class="logo" width="70cm">
    <center>
        <h1 style="font-size: 20px;">PURCHASE REQUEST</h1>
        <h1 style="font-size: 15px;">No : <?= ucwords($no_pr); ?></h1>
        <br>
        <?php if ($status == 0) {
            echo 'status : <h1 style="font-size: 15px; color:orange;">Open (Butuh approval atasan)</h1>';
        } elseif ($status == 1) {
            echo 'status : <h1 style="font-size: 15px; color:orange">Approved Atasan (menunggu suggest spec (IT))</h1>';
        } elseif ($status == 2) {
            echo 'status : <h1 style="font-size: 15px; color:orange"">Suggested spec (menunggu final approval (finance))</h1>';
        } elseif ($status == 3) {
            echo 'status : <h1 style="font-size: 15px; color:orange""><b>Searching Asset by Purchasing</b></h1>';
        } elseif ($status == 4) {
            echo 'status : <h1 style="font-size: 15px; color:orange"">APPROVE FINANCE</h1>';
        } else {
            echo 'status : <h1 style="font-size: 15px; color:orange"">REJECTED</h1>';
        }
        ?>
    </center>
    <br><br>

    <u>Diajukan oleh (karyawan) :</u><br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Divisi</td>
                <td width="2px">:</td>
                <td width="350px"><?= ucwords($divisi); ?></td>
                <td width="50px">Tanggal</td>
                <td width="2px">:</td>
                <td><?= date('d F Y', strtotime($created_at)); ?></td>
            </tr>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px"><?= ucwords($username); ?></td>
            </tr>
            <tr>
                <td width="100px">Barang</td>
                <td width="2px">:</td>
                <td width="350px"><?= ucwords($barang); ?></td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <u>Disetujui oleh (atasan) :</u><br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($username_atasan == null || $username_atasan == '') {
                        echo '-';
                    } else {
                        echo ucwords($username_atasan);
                    }
                    ?>
                </td>
                <?php if ($tgl_konfirmasi_atasan != null || $tgl_konfirmasi_atasan != "") { ?>
                    <td width="50px">Tanggal</td>
                    <td width="2px">:</td>
                    <td><?= date('d F Y', strtotime($tgl_konfirmasi_atasan)); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td width="100px">Keterangan</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($keterangan_atasan == null || $keterangan_atasan == '') {
                        echo '-';
                    } else {
                        echo ucwords($keterangan_atasan);
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <u>Rekomendasi spesifikasi oleh (IT) :</u><br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($username_it == null || $username_it == '') {
                        echo '-';
                    } else {
                        echo ucwords($username_it);
                    }
                    ?>
                </td>
                <?php if ($tgl_konfirmasi_it != null || $tgl_konfirmasi_it != "") { ?>
                    <td width="50px">Tanggal</td>
                    <td width="2px">:</td>
                    <td><?= date('d F Y', strtotime($tgl_konfirmasi_it)); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td width="100px">Spesifikasi</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($spesifikasi == null || $spesifikasi == '') {
                        echo '-';
                    } else {
                        echo ucwords($spesifikasi);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="100px">Keterangan</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($keterangan_it == null || $keterangan_it == '') {
                        echo '-';
                    } else {
                        echo ucwords($keterangan_it);
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <u>Di pesan oleh (Purchasing) :</u><br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($username_purchasing == null || $username_purchasing == '') {
                        echo '-';
                    } else {
                        echo ucwords($username_purchasing);
                    }
                    ?>
                </td>
                <?php if ($tgl_konfirmasi_purchasing != null || $tgl_konfirmasi_purchasing != "") { ?>
                    <td width="50px">Tanggal</td>
                    <td width="2px">:</td>
                    <td><?= date('d F Y', strtotime($tgl_konfirmasi_purchasing)); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td width="100px">Keterangan</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($keterangan_purchasing == null || $keterangan_purchasing == '') {
                        echo '-';
                    } else {
                        echo ucwords($keterangan_purchasing);
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <u>Di setujui oleh (Finance) :</u><br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($username_finance == null || $username_finance == '') {
                        echo '-';
                    } else {
                        echo ucwords($username_finance);
                    }
                    ?>
                </td>
                <?php if ($tgl_konfirmasi_finance != null || $tgl_konfirmasi_finance != "") { ?>
                    <td width="50px">Tanggal</td>
                    <td width="2px">:</td>
                    <td><?= date('d F Y', strtotime($tgl_konfirmasi_finance)); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td width="100px">Keterangan</td>
                <td width="2px">:</td>
                <td width="350px">
                    <?php if ($keterangan_finance == null || $keterangan_finance == '') {
                        echo '-';
                    } else {
                        echo ucwords($keterangan_finance);
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <?php if ($status == 4) { ?>
        <table border="0" width="100%">
            <tr>
                <td align="right">Disetujui oleh,</td>
            </tr>

            <tr>
                <td align="right"><img src="assets_new/images/verified.png" alt="mulia putra mandiri" class="imgverified" style="width: 80px;"></td>
            </tr>

            <tr>
                <!-- <td align="right"><?= ucwords($username_finance); ?><< /td> -->
                <td align="right"><?= "Finance" ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>

        </table>
    <?php } ?>

</body>

</html>