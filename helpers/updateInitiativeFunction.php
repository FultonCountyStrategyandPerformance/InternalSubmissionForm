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
      $update_string = constructInitiativeQuery($table, $fiscal_year,
                                        $quarter,
                                        $current_date_time ,
                                        $editor,
                                        $key,
                                        $value);
      echo "<script>console.log(`".$update_string."`)</script>";
      $update_result=true;
      //$update_result = odbc_exec($conn, $update_string);
      if($update_result) {
        echo "success";
        //$rows_updated += odbc_num_rows($update_result);
      }
      else {
        print("".odbc_errormsg($conn));
        return array (false, 0);
      }
    }
  return array (true, $rows_updated);
}

// Query String Constructor
function constructInitiativeQuery($table, $fiscal_year,
  $quarter,$date,$editor,
  $Department,$Initiative,
  $Budget,$ImpactStatement,
  $Progress) {
  // Check if Value exists
  $query = "IF EXISTS (SELECT *
        FROM ".$table."
        WHERE Year = ".$fiscal_year."
        AND Quarter =".$quarter."
        AND Initiative LIKE '".$Initiative."')\n";
  // If it already exists update it
  $query .= "UPDATE ".$table."
    SET Progress = ".$Progress.", Editor = '".$editor."', LastEdit = '".$date."'
    WHERE Year = ".$fiscal_year."
    AND Quarter = ".$quarter."
    AND Initative LIKE ".$Initiative."\n";
  // If not Create it
  $query .= "ELSE INSERT INTO ".$table."
    (Year, Quarter, Department, Initiative, Budget, ImpactStatement, Progress, Editor, LastEdit)
    VALUES (".$fiscal_year.",".$quarter.",'".$department."','".$date."','".$editor."',".$value.");\n";
  return $query;
}
?>
