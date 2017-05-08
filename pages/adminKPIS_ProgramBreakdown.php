<?php
    //
    $programs = "SELECT ProgramName
      FROM ".$performance_program_kpis."
      WHERE DepartmentID=".round($department['DepartmentID'])." AND Active=1
      GROUP BY ProgramName";

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
        echo "<h4>".$program['ProgramName']."</h4>";

        // Get KPIs for that program
        $kpis = "SELECT *
          FROM ".$performance_program_kpis."
          WHERE DepartmentID=".round($department['DepartmentID'])."
          AND ProgramName='".$program['ProgramName']."'
          AND Active = 1";

          $kpi_result = odbc_exec($conn, $kpis);
          if(!$kpi_result) {
            echo odbc_errormsg();
          }
          $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
          while($row = odbc_fetch_array($kpi_result)) {

              $q = "SELECT * FROM ".$performance_program_values_staging."
                    WHERE  Year =".$fiscal_year."
                    AND Quarter =".$quarter."
                    AND MeasureID = ".round($row['MeasureID']);
              $r = odbc_exec($conn, $q);
              if(odbc_num_rows($r)==0) {
                $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td style='width:15%'>".
                "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='0'/></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr></tr>";
              }
              else {
                $r = odbc_exec($conn, $q);
                $v = odbc_fetch_array($r, 1);
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
