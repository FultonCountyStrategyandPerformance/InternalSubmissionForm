<?php
    //
    // Get the current values (if the are there)
    $department_result = odbc_exec($conn, $departments);
    if(!$department_result) {
      echo odbc_errormsg();
    }

    $first_department = odbc_fetch_array($department_result,1);

    $current_value = "SELECT *
      FROM ".$performance_program_kpis." as k
      FULL OUTER JOIN ".$performance_program_values." as v
        ON k.MeasureID = v.MeasureID
      WHERE  v.YEAR =".$fiscal_year."
      AND v.Quarter =".$quarter."
      AND k.DepartmentID =".round($first_department['DepartmentID']);

    // Get all the KPI measure IDs and Names
    if(sizeof($first_department) > 1) {
        $programs = "SELECT Program
          FROM ".$performance_program_kpis."
          WHERE DepartmentID=".round($first_department['DepartmentID'])."
          GROUP BY Program";

        $program_result = odbc_exec($conn, $programs);
        // Handle any Errors
        if(!$program_result) {
          echo odbc_errormsg();
        }

        // Check if there are any kpis
        elseif(odbc_num_rows($program_result) == 0) {
            echo "No KPI's Available";
        }
        else {
          while($program = odbc_fetch_array($program_result)) {
            echo "<h4>".$program['Program']."</h4>";

            // Get KPIs for that program
            $kpis = "SELECT *
              FROM ".$performance_program_kpis."
              WHERE DepartmentID=".round($first_department['DepartmentID'])."
              AND Program='".$program['Program']."'";

              $kpi_result = odbc_exec($conn, $kpis);
              if(!$kpi_result) {
                echo odbc_errormsg();
              }
              $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
              while($row = odbc_fetch_array($kpi_result)) {

                  $q = "SELECT * FROM ".$performance_program_values."
                        WHERE  Year =".$fiscal_year."
                        AND Quarter =".$quarter."
                        AND MeasureID = ".round($row['MeasureID']);
                  $r = odbc_exec($conn, $q);
                  if(odbc_num_rows($r)==0) {
                    $grid .= "<tr><td class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td>".
                    "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' value='0'/></td></tr>";
                  }
                  else {
                    $r = odbc_exec($conn, $q);
                    $v = odbc_fetch_array($r, 1);
                    $grid .= "<tr><td class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td>".
                    "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' value='".$v['Value']."'/></td><td id='unit'>".$v["ValueType"]."</td></tr>";
                  }
              }
              $grid .= "</table>";
              echo $grid;
          }
          echo "<input class='submit' type='submit' name='btnsubmit'>";
      }
    }
    //
?>
