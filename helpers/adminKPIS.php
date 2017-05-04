<?php
    //
    // Get the current values (if the are there)
    $department_result = odbc_exec($conn, $departments);
    if(!$department_result) {
      echo odbc_errormsg();
    }

    $first_department = odbc_fetch_array($department_result,1);

    // Get all the KPI measure IDs and Names
    if(sizeof($first_department) > 1) {
            // Get KPIs for that program
            $kpis = "SELECT *
              FROM ".$performance_program_kpis." k
              JOIN ".$performance_program_values." v ON
              k.MeasureID = v.MeasureID
              WHERE v.Year =".$fiscal_year."
              AND v.Quarter = ".$quarter."
              AND k.DepartmentID=".round($first_department['DepartmentID']);

              $kpi_result = odbc_exec($conn, $kpis);

              // Handle KPI error
              if(!$kpi_result) {
                echo odbc_errormsg();
              }

              // If there are no KPI's fill it in with last quarter's data and 0 out the value
              if(odbc_num_rows($kpi_result) == 0) {
                $last_quarter = 0;
                if($quarter == 2) {
                  $last_quarter = 4;
                  $fiscal_year = $fiscal_year - 1;
                }
                else {
                  $last_quarter = $quarter - 1;
                }

                $old_kpis = "SELECT *
                  FROM ".$performance_program_kpis." k
                  JOIN ".$performance_program_values." v ON
                  k.MeasureID = v.MeasureID
                  WHERE v.Year =".$fiscal_year."
                  AND v.Quarter = ".$last_quarter."
                  AND k.DepartmentID=".round($first_department['DepartmentID']);

                $old_result = odbc_exec($conn, $old_kpis);

                if(!$old_result) {
                  echo odbc_errormsg();
                }
                $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
                while($row = odbc_fetch_array($old_result)) {
                  $grid .= "<tr><td class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td>".
                  "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' value='0'/></td><td id='unit'>".$row["ValueType"]."</td></tr>";
                }
                $grid .= "</table>";

              }
              else {
                $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
                while($row = odbc_fetch_array($kpi_result)) {
                      $grid .= "<tr><td class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td>".
                      "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' value='".$row["Value"]."'/></td><td id='unit'>".$row["ValueType"]."</td></tr>";
                }
                $grid .= "</table>";
              }
          echo $grid;
          echo "<input class='submit' type='submit' name='btnsubmit'>";
    }
    //
?>
