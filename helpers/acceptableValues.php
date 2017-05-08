<?php function acceptableValues($conn, $department, $staging_table, $kpi_table, $quarter, $year) {
  $deviation_array = array();
  $deviation_query = constructDeviationQuery($department, $staging_table, $kpi_table, $quarter, $year);
  $deviation_result = odbc_exec($conn, $deviation_query);
  while($row = odbc_fetch_array($deviation_result)){
    $deviation_array[$row['MeasureID']] = array();
    $deviation_array[$row['MeasureID']]['StdDev'] = $row['StdDev'];
    $deviation_array[$row['MeasureID']]['Avg'] = $row['Average'];
  }
  return $deviation_array;
}
function constructDeviationQuery($department, $staging_table, $kpi_table, $quarter, $year) {
  $query = "SELECT STDEV(CAST(Value as float)) as StdDev
    , AVG(CAST(Value as float)) as Average
    , v.MeasureID
    FROM ".$staging_table." v
    JOIN ".$kpi_table." k ON
    v.MeasureID = k.MeasureID
    WHERE k.DepartmentID=".round($department)."
    AND k.Active = 1
    AND v.Year < ".round($year)."
    GROUP BY v.MeasureID";
  return $query;
}
?>
