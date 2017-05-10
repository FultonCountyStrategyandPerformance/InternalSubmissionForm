<?php
function validate($conn, $user, $password, $users_table) {
  $user_query = "SELECT *
    FROM ".$users_table."
    WHERE user_name like '".$user."' AND user_password = '".$password."'";
  echo "<script>console.log('".$user_query."')</script>";
  $user_result = sqlsrv_query($conn, $user_query);
  if(!$user_result) {
    echo sqlsrv_errors();
  }
  if(sqlsrv_num_rows($user_result) == 0) {
    return array (false, $user,"", "NONE", 0);
  }
  else {
    $department = sqlsrv_fetch_array($user_result,1);
    return array (true, $user, $password,$department['department'],$department["department_head"]);
  }
}
?>
