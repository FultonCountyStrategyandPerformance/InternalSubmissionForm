<?php
// Update KPI function
function updateKPI($fiscal_year, $quarter, $editor, $value_array, $conn, $updateConn, $table) {
  // Handle the submission datetime
  date_default_timezone_set("America/New_York");
  $current_date_time = Date('Y-m-d H:i:s');

  // Create query strings and do the update
  $rows_updated = 0;
  foreach ($value_array as $key => $value) {
    $update_string = "";
    // Check to see what values have been updated
    if(diff($table,$fiscal_year, $quarter, $key, $value, $conn))
    {
      $update_string = constructKPIQuery($table,$fiscal_year,
                                        $quarter,
                                        $current_date_time ,
                                        $editor,
                                        $key,
                                        $value);
      }

      $update_result = sqlsrv_query($updateConn, $update_string, array(), array("Scrollable"=>"static"));
      // Check for Errors
      if( ($errors = sqlsrv_errors() ) != null) {
          foreach( $errors as $error ) {
              echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
              echo "code: ".$error[ 'code']."<br />";
              echo "message: ".$error[ 'message']."<br />";
          }
      }
      $updated = sqlsrv_num_rows($update_result);
      if($updated > 0) {
        $rows_updated += sqlsrv_num_rows($update_result);
      }
    }

    if($rows_updated == -1 || $rows_updated == 0) {
      return array (false, 0);
    }
    else {
      return array (true, $rows_updated);
    }
}

// Query String Constructor
function constructKPIQuery($table,$fiscal_year, $quarter, $date, $editor, $measureID, $value) {
  // Check if Value exists
  $query = "IF EXISTS (SELECT *
        FROM ".$table."
        WHERE Year = ".$fiscal_year."
        AND Quarter =".$quarter."
        AND MeasureID=".$measureID.")\n";
  // If it already exists update it
  $query .= "UPDATE ".$table."
    SET Value = ".$value.", Editor = '".$editor."', LastEdit = '".$date."'
    WHERE Year = ".$fiscal_year."
    AND Quarter = ".$quarter."
    AND MeasureID = ".$measureID."\n";
  // If not Create it
  $query .= "ELSE INSERT INTO ".$table."
    (MeasureID, Year, Quarter, LastEdit, Editor, Value)
    VALUES (".$measureID.",".$fiscal_year.",".$quarter.",'".$date."','".$editor."',".$value.");\n";
  return $query;
}

// DIFF FUNCTION
function diff($table,$fiscal_year, $quarter, $measureID, $value, $conn) {
  if($value == "") {
    return false;
  }
  $current_value_query = "SELECT Value
      FROM ".$table."
      WHERE Year = ".$fiscal_year." AND Quarter = ".$quarter."
        AND measureID = ".round($measureID);
  $current_value_result = sqlsrv_query($conn, $current_value_query, array(),array("Scrollable"=>"static"));
  // Check for Errors
  if( ($errors = sqlsrv_errors() ) != null) {
      foreach( $errors as $error ) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
      }
  }
  while($current_value = sqlsrv_fetch_array($current_value_result)) {
    if($current_value['Value'] == $value) {
      return false;
    }
  }
  return true;

}
?>
