<?php
function validate($conn, $user) {
  $user_query = "SELECT username, departmentID
    FROM fulton_county.dbo.users
    WHERE username like '".$user."'";
  echo "<script>console.log(`".$user_query."`)</script>";
  $user_result = odbc_exec($conn, $user_query);
  if(odbc_num_rows($user_result) == 0) {
    return array (false, $user, "NONE");
  }
  else {
    $department = odbc_fetch_array($user_result,1);
    return array (true, $user, $department['departmentID']);
  }
}
?>
