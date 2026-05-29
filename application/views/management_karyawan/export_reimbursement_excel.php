<!-- ===================================================== -->
<!-- VIEW -->
<!-- export_reimbursement_excel.php -->
<!-- TOTAL DIPISAH -->
<!-- ===================================================== -->

<?php
$bulanNama = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];

$pegawai = [];

foreach($rows as $r){

    if(!isset($pegawai[$r->id_karyawan])){
        $pegawai[$r->id_karyawan] = [
            'nama' => $r->nama_lengkap,
            'data' => [],
            'total_pengobatan' => 0,
            'total_kacamata'   => 0
        ];
    }

    $pegawai[$r->id_karyawan]['data'][$r->bulan][$r->id_kategori] = $r->total;

    // kategori 2 = Pengobatan
    if($r->id_kategori == 2){
        $pegawai[$r->id_karyawan]['total_pengobatan'] += $r->total;
    }

    // kategori 1 = Kacamata
    if($r->id_kategori == 1){
        $pegawai[$r->id_karyawan]['total_kacamata'] += $r->total;
    }
}
?>

<table border="1">

    <!-- HEADER 1 -->
    <tr style="background:#ffff00;font-weight:bold;">
        <th rowspan="2">No</th>
        <th rowspan="2">Nama</th>

        <?php for($b=1;$b<=12;$b++): ?>
            <th colspan="2"><?= $bulanNama[$b] ?></th>
        <?php endfor; ?>

        <th colspan="2">Total</th>
    </tr>

    <!-- HEADER 2 -->
    <tr style="background:#ffff00;font-weight:bold;">
        <?php for($b=1;$b<=12;$b++): ?>
            <th>Pengobatan</th>
            <th>Kacamata</th>
        <?php endfor; ?>

        <th>Pengobatan</th>
        <th>Kacamata</th>
    </tr>

<?php
$no=1;
foreach($pegawai as $p):
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p['nama'] ?></td>

    <?php for($b=1;$b<=12;$b++): 
        
        $pengobatan = isset($p['data'][$b][2]) ? $p['data'][$b][2] : 0;
        $kacamata   = isset($p['data'][$b][1]) ? $p['data'][$b][1] : 0;
    ?>

        <td><?= $pengobatan ?></td>
        <td><?= $kacamata ?></td>

    <?php endfor; ?>

    <!-- TOTAL DIPISAH -->
    <td><b><?= $p['total_pengobatan'] ?></b></td>
    <td><b><?= $p['total_kacamata'] ?></b></td>

</tr>

<?php endforeach; ?>

</table>