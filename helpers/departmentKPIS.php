<?php
    // Get all the current values (if the are there) FOR DEBUGGING
    $current_value = "SELECT *
      FROM fulton_county.dbo.PerformanceManagement_ProgramKPIs as k
      FULL OUTER JOIN fulton_county.dbo.PerformanceManagement_ProgramValues as v
        ON k.MeasureID = v.MeasureID
      WHERE  v.YEAR =".$fiscal_year."
      AND v.Quarter =".$quarter."
      AND k.DepartmentID =".round($_POST['department']);


      $programs = "SELECT Program
        FROM fulton_county.dbo.PerformanceManagement_ProgramKPIs
        WHERE DepartmentID=".round($_POST['department'])."
        GROUP BY Program";
      echo "<script>console.log(`$programs`)</script>";
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
            FROM fulton_county.dbo.PerformanceManagement_ProgramKPIs
            WHERE DepartmentID=".round($_POST['department'])."
            AND Program='".$program['Program']."'";

            $kpi_result = odbc_exec($conn, $kpis);
            if(!$kpi_result) {
              echo odbc_errormsg();
            }
            $grid = "<table><tr><th>Measure</th><th>Value</th></tr>";
          while($row = odbc_fetch_array($kpi_result)) {
              $q = "SELECT * FROM fulton_county.dbo.PerformanceManagement_ProgramValues
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
                "<input name='kpi_values[".round($row['MeasureID'])."]' type='number' value='".$v['Value']."'/></td><tr>";
              }
          }
          $grid .= "</table>";
      echo $grid;
      }
      echo "<input class='submit' type='submit' name='btnsubmit'>";
  }



?>
