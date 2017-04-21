<?php
// Update KPI function
function updateKPI($fiscal_year, $quarter, $editor, $value_array, $conn, $table) {
  $update_string = "";
  // Handle the submission datetime
  date_default_timezone_set("America/New_York");
  $current_date_time = Date('d-m-Y H:i:s');
  // Create query strings and do the update
  $rows_updated = 0;
  foreach ($value_array as $key => $value) {
    // Check to see what values have been updated
    if(diff($table,$fiscal_year, $quarter, $key, $value, $conn))
    {
      $update_string = constructKPIQuery($table,$fiscal_year,
                                        $quarter,
                                        $current_date_time ,
                                        $editor,
                                        $key,
                                        $value);
      echo "<script>console.log(`".$update_string."`)</script>";
      $update_result = odbc_exec($conn, $update_string);
      if($update_result) {
        $rows_updated += odbc_num_rows($update_result);
      }
      else {
        print("".odbc_errormsg($conn));
        return array (false, 0);
      }
    }
  }
  return array (true, $rows_updated);
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
    (MeasureID, Year, Quarter, ValueType, LastEdit, Editor, Value)
    VALUES (".$measureID.",".$fiscal_year.",".$quarter.","."''".",'".$date."','".$editor."',".$value.");\n";
  return $query;
}

// DIFF FUNCTION
function diff($table,$fiscal_year, $quarter, $measureID, $value, $conn) {
  $current_value_query = "SELECT Value
      FROM ".$table."
      WHERE Year = ".$fiscal_year." AND Quarter = ".$quarter."
        AND measureID = ".round($measureID);
  $current_value_result = odbc_exec($conn, $current_value_query);
  if(!$current_value_result) {
    echo odbc_errormsg();
  }
  $current_value = odbc_fetch_array($current_value_result);
  if($current_value['Value'] == $value) {
    return false;
  }
  else {
    return true;
  }
}
?>
