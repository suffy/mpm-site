<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Karyawan</title>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .header-text {
            text-align: center;
            padding-right: 80px; /* Kompensasi agar teks benar-benar di tengah (sama dengan lebar logo) */
        }

        .header-table td {
            border: none; /* Hilangkan border untuk header */
            vertical-align: middle;
        }

        .logo {
            width: 120px; /* Atur besar logo sesuai kebutuhan */
        }

        .header-text h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .section {
            margin-top: 15px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            background: #f0f0f0;
            padding: 6px;
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        th {
            width: 30%;
            background: #fafafa;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <!-- <style>
        /* ... style lama Anda ... */

        

        .header-table td {
            border: none; /* Hilangkan border untuk header */
            vertical-align: middle;
        }

        .logo {
            width: 120px; /* Atur besar logo sesuai kebutuhan */
        }

        

        .header-text h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
    </style> -->
</head>
<body>

<!-- <div class="title">DATA KARYAWAN</div> -->

<table class="header-table">
    <tr>
        <td width="15%">
            <img src="./assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
        </td>
        <td class="header-text">
            <h1>DATA KARYAWAN</h1>
            <div style="font-size: 12px; font-weight: normal;">PT. MULIA PUTRA MANDIRI</div>
        </td>
    </tr>
</table>

<hr style="border: 1px solid #000; margin-top: -10px; margin-bottom: 20px;">

<!-- ================= DATA PRIBADI ================= -->
<div class="section">
    <div class="section-title">DATA PRIBADI</div>
    <table>
        <tr><th>Nama Lengkap</th><td><?= $karyawan->nama_lengkap ?></td></tr>
        <tr><th>Username Web</th><td><?= $karyawan->username_web ?></td></tr>
        <tr><th>Site Code</th><td><?= $karyawan->site_code ?></td></tr>
        <tr><th>Sub Branch / DP</th><td><?= $karyawan->nama_comp ?></td></tr>
        <tr><th>No. Kepegawaian</th><td><?= $karyawan->nomor_kepegawaian ?></td></tr>
        <tr><th>Jenis Kelamin</th><td><?= $karyawan->jenis_kelamin ?></td></tr>
        <tr><th>Tempat Lahir</th><td><?= $karyawan->tempat_lahir ?></td></tr>
        <tr><th>Tanggal Lahir</th><td><?= $karyawan->tanggal_lahir ?></td></tr>
        <tr><th>Golongan Darah</th><td><?= $karyawan->golongan_darah ?></td></tr>
        <tr><th>Status Perkawinan</th><td><?= $karyawan->status_perkawinan ?></td></tr>
        <tr><th>Agama</th><td><?= $karyawan->agama ?></td></tr>
        <tr><th>Alamat KTP</th><td><?= $karyawan->alamat_ktp ?></td></tr>
        <tr><th>Alamat Domisili</th><td><?= $karyawan->alamat_domisili ?></td></tr>
        <tr><th>Email Pribadi</th><td><?= $karyawan->email ?></td></tr>
        <tr><th>Email Perusahaan</th><td><?= $karyawan->email_perusahaan ?></td></tr>
        <tr><th>No. HP</th><td><?= $karyawan->phone ?></td></tr>
        <tr><th>Kontak Darurat</th><td><?= $karyawan->nama_kontak_darurat ?> (<?= $karyawan->nomor_kontak_darurat ?>)</td></tr>
    </table>
</div>

<!-- ================= DATA KEPEGAWAIAN ================= -->
<div class="section">
    <div class="section-title">DATA KEPEGAWAIAN</div>
    <table>
        <tr><th>Status Karyawan</th><td><?= $karyawan->status_karyawan ?></td></tr>
        <tr><th>Tanggal Mulai Kerja</th><td><?= $karyawan->tanggal_mulai_kerja ?></td></tr>
        <tr><th>Tanggal Selesai Kerja</th><td><?= $karyawan->tanggal_selesai_kerja ?></td></tr>
        <tr><th>Departement</th><td><?= $karyawan->departement ?></td></tr>
        <tr><th>Divisi</th><td><?= $karyawan->divisi ?></td></tr>
        <tr><th>Job Level</th><td><?= $karyawan->job_level ?></td></tr>
        <tr><th>Atasan Langsung</th><td><?= $karyawan->nama_atasan_langsung ?></td></tr>
    </table>
</div>

<!-- ================= DOKUMEN ================= -->
<div class="section">
    <div class="section-title">DATA DOKUMEN</div>
    <table>
        <tr><th>No. KTP</th><td><?= $karyawan->nomor_ktp ?></td></tr>
        <tr><th>No. KK</th><td><?= $karyawan->nomor_kk ?></td></tr>
        <tr><th>NPWP</th><td><?= $karyawan->npwp ?></td></tr>
        <!-- <tr><th>BPJS Ketenagakerjaan</th><td><?= $karyawan->nomor_bpjs_ketenagakerjaan ?></td></tr>
        <tr><th>BPJS Kesehatan</th><td><?= $karyawan->nomor_bpjs_kesehatan ?></td></tr> -->
    </table>
</div>

<!-- ================= BANK ================= -->
<div class="section">
    <div class="section-title">DATA BANK</div>
    <table>
        <tr><th>Nama Bank</th><td><?= $karyawan->nama_bank ?></td></tr>
        <tr><th>No. Rekening</th><td><?= $karyawan->nomor_rekening ?></td></tr>
        <tr><th>Nama Rekening</th><td><?= $karyawan->nama_rekening ?></td></tr>
    </table>
</div>

<!-- ================= PENDIDIKAN ================= -->
<div class="section">
    <div class="section-title">RIWAYAT PENDIDIKAN</div>
    <table>
        <tr>
            <th>Jenjang</th>
            <th>Institusi</th>
            <th>Jurusan</th>
        </tr>
        <?php if (!empty($list_pendidikan)) {
            foreach ($list_pendidikan as $p) { ?>
            <tr>
                <td><?= $p->pendidikan_terakhir ?></td>
                <td><?= $p->institusi_pendidikan ?></td>
                <td><?= $p->jurusan ?></td>
            </tr>
        <?php }} ?>
    </table>
</div>

<!-- ================= KELUARGA ================= -->
<div class="section">
    <div class="section-title">DATA KELUARGA</div>
    <table>
        <tr>
            <th>Nama</th>
            <th>Hubungan</th>
            <th>Pendidikan</th>
            <th>Pekerjaan</th>
        </tr>
        <?php if (!empty($list_keluarga)) {
            foreach ($list_keluarga as $k) { ?>
            <tr>
                <td><?= $k->nama ?></td>
                <td><?= $k->hubungan ?></td>
                <td><?= $k->pendidikan ?></td>
                <td><?= $k->pekerjaan ?></td>
            </tr>
        <?php }} ?>
    </table>
</div>

<!-- ================= ASURANSI ================= -->
<!-- <div class="section">
    <div class="section-title">DATA ASURANSI</div>
    <table>
        <tr>
            <th>No. Kartu</th>
            <th>No. Polis</th>
            <th>Plan</th>
            <th>No. Peserta</th>
        </tr>
        <?php if (!empty($list_asuransi)) {
            foreach ($list_asuransi as $a) { ?>
            <tr>
                <td><?= $a->nomor_kartu_asuransi ?></td>
                <td><?= $a->nomor_polis_asuransi ?></td>
                <td><?= $a->plan_asuransi ?></td>
                <td><?= $a->nomor_peserta_asuransi ?></td>
            </tr>
        <?php }} ?>
    </table>
</div> -->

<br><br>
<div class="text-center">
    Dicetak pada: <?= date('d-m-Y H:i') ?>
</div>

</body>
</html>
