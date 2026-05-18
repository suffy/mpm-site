<?php
include "../setting.php";
include_once('../class/fpdf/fpdf.php');
include_once("../class/PHPJasperXML.inc.php");


$xml =  simplexml_load_file("table1.jrxml");


$PHPJasperXML = new PHPJasperXML();
  
$PHPJasperXML->debugsql=false;
$PHPJasperXML->arrayParameter=array("parameter1"=>1);
$PHPJasperXML->xml_dismantle($xml);

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
//$PHPJasperXML->showLineChart("SDA");

$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file
//$PHPJasperXML->showLineChart();

?>
