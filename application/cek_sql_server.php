<?php 
//tcp:192.168.7.3,1433
//
$connection = sqlsrv_connect('tcp:192.168.7.3,1433', ['UID' => 'sa', 'PWD' => 'mpm12345']);
$pdo = new PDO("sqlsrv:Server=tcp:192.168.7.3,1433;", "sa", "mpm12345");

if($connection){
    echo "koneksi ke sql server berhasil";
}

if($pdo){
    echo "koneksi ke sql server pdo berhasil";
}

?>