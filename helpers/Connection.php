<?php
// Server Settings
$connectionInfo = array("UID"=>"GISViewer", "PWD"=>"gisviewer","Database"=>"");
$serverName="GISPubDb";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if(!$conn) {
  die(sqlsrv_errors());
}
?>
