<?php
// Server Settings
$connectionInfo = array("UID"=>"", "PWD"=>"","Database"=>"")
$serverName="";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if(!$conn) {
  die(sqlsrv_errors());
}
?>
