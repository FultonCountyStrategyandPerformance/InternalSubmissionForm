<?php
function validate($conn, $user, $password, $users_table) {
  $user_query = "SELECT *
    FROM ".$users_table."
    WHERE user_name like '".$user."' AND user_password = '".$password."'";
  $user_result = sqlsrv_query($conn, $user_query);
  if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
  }
  while($row=sqlsrv_fetch_array($user_result)) {
      if($row['user_name']) {
        return array (true, $user, $password,$row['department'],$row["department_head"]);
      }
      else {
        return array (false, $user,"", "NONE", 0);
      }
  }
}
?>
