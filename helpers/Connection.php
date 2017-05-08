<?php
// Server Settings
$serverName="PETERMOORE-PC\SQLEXPRESS";
$user='sa';
$password='Glenlake!070288';
$database="fulton_county";
$conn = odbc_connect("Driver={SQL Server Native Client 10.0};Server=$serverName;Database=$database;", $user, $password);
if(!$conn) {
  die(odbc_errormsg());
}
?>
