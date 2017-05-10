<?php
    //
    $programs = "SELECT ProgramName
      FROM ".$performance_program_kpis."
      WHERE DepartmentID=".round($department_id)." AND Active=1
      GROUP BY Program";

    $program_result = sqlsrv_query($conn, $programs);
    // Handle any Errors
    if(!$program_result) {
      echo sqlsrv_errors();
    }

    // Check if there are any kpis
    else {
      while($program = sqlsrv_fetch_array($program_result)) {
        echo "<h4>".$program['Program']."</h4>";

        // Get KPIs for that program
        $kpis = "SELECT *
          FROM ".$performance_program_kpis."
          WHERE DepartmentID=".round($department['DepartmentID'])."
          AND Program='".$program['Program']."'
          AND Active = 1";

          $kpi_result = sqlsrv_query($conn, $kpis);
          if(!$kpi_result) {
            echo sqlsrv_errors();
          }
          $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
          while($row = sqlsrv_fetch_array($kpi_result)) {

              $q = "SELECT * FROM ".$performance_program_values_staging."
                    WHERE  Year =".$fiscal_year."
                    AND Quarter =".$quarter."
                    AND MeasureID = ".round($row['MeasureID']);
              $r = sqlsrv_query($conn, $q);
              if(sqlsrv_num_rows($r)==0) {
                $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td style='width:15%'>".
                "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='0'/></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr></tr>";
              }
              else {
                $r = sqlsrv_query($conn, $q);
                $v = sqlsrv_fetch_array($r, 1);
                $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td style='width:15%'>".
                "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".$v['Value']."'/></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr>";
              }
          }
          $grid .= "</table>";
          echo $grid;
      }
      echo "<input type='checkbox' name='valid' value='agreed' required>I verify that this data is correct<br>";
      echo "<input class='submit' type='submit' name='adminsubmit'>";
  }
    //
?>
