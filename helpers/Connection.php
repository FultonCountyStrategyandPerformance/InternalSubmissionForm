<?php
// Server Settings
$connectionInfo = array("UID"=>"", "PWD"=>"",""=>"");
$serverName="GISPubDb";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if(!$conn) {
  if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
  }
}

$updateInfo = array("UID"=>"", "PWD"=>"","Database"=>"");
$updateConn = sqlsrv_connect($serverName, $updateInfo);
if(!$updateConn) {
  if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
  }
}
?>
