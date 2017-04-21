<?php
// Server Settings
$serverName="";
$user='';
$password='';
$database="";
$conn = odbc_connect("Driver={SQL Server Native Client 10.0};Server=$serverName;Database=$database;", $user, $password);
if(!$conn) {
  die(odbc_errormsg());
}
?>
