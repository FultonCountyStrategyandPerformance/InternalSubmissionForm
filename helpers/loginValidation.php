<?php
function validate($conn, $user, $password, $users_table) {
  $user_query = "SELECT *
    FROM ".$users_table."
    WHERE user_name like '".$user."' AND user_password = '".$password."'";
  echo "<script>console.log('".$user_query."')</script>";
  $user_result = sqlsrv_query($conn, $user_query);
  if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
  }
  if(sqlsrv_num_rows($user_result) == 0) {
    while($row=sqlsrv_fetch_array($user_result)) {
      echo $row['user_name'];
    }
    return array (false, $user,"", "NONE", 0);
  }
  else {
    $department = sqlsrv_fetch_array($user_result,1);
    return array (true, $user, $password,$department['department'],$department["department_head"]);
  }
}
?>
