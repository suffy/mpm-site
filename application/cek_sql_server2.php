<?php
$serverName = "192.168.7.3"; 
$connectionInfo = array( "UID" => "sa", "PWD" => "mpm12345", "Database" => "jkt" );
$link = sqlsrv_connect( $serverName, $connectionInfo );
if( $link ) {
     echo "Connection established.
";
} else{
     echo "Connection could not be established.
";
     die( print_r( sqlsrv_errors(), true ) );
}
$sql = "select * from JKT.dbo.m_customer"; // isi nama table yg ada
 
$stmt = sqlsrv_query( $link, $sql );
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
  echo $row['customerid']."
";
}
 
if( $stmt === false ) {
  die( print_r( sqlsrv_errors(), true));
}

?>