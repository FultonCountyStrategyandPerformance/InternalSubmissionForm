<?php
    // Get all the KPI measure IDs and Names
    $programs = "SELECT Program
      FROM ".$performance_program_kpis."
      WHERE DepartmentID=".round($department_id)."
      GROUP BY Program";

    $program_result = sqlsrv_query($conn, $programs);
    // Handle any Errors
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

    // Check if there are any kpis
    elseif(sqlsrv_num_rows($program_result) == 0) {
        echo "No KPI's Available";
    }
    else {
      while($program = sqlsrv_fetch_array($program_result)) {
        echo "<h4>".$program['Program']."</h4>";

        // Get KPIs for that program
        $kpis = "SELECT *
          FROM ".$performance_program_kpis."
          WHERE DepartmentID=".round($department_id)."
          AND Program='".$program['Program']."'
          AND Active = 1";

          $kpi_result = sqlsrv_query($conn, $kpis);
          if( ($errors = sqlsrv_errors() ) != null) {
            echo "ERROR GETTING DEPARTMENT RESULT";
              foreach( $errors as $error ) {
                  echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                  echo "code: ".$error[ 'code']."<br />";
                  echo "message: ".$error[ 'message']."<br />";
              }
          }
          $grid = "<table><tr><th>Measure</th><th>Value</th><th>Unit</th></tr>";
          while($row = sqlsrv_fetch_array($kpi_result)) {

              $q = "SELECT * FROM ".$performance_program_values_staging."
                    WHERE  Year =".$fiscal_year."
                    AND Quarter =".$quarter."
                    AND MeasureID = ".round($row['MeasureID']);
              $r = sqlsrv_query($conn, $q);
              if(sqlsrv_num_rows($r)==0) {

                // TODO: Split out count and percent warnings

                $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td class='validation' style='width:15%'>".
                "<input id='".round($row['MeasureID'])."' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='0' required/><span class='validtext'>".$count_warning_text."</span></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr></tr>";
              }
              else {
                $r = sqlsrv_query($conn, $q);
                $v = sqlsrv_fetch_array($r, 1);
                if($row['Unit'] == 'PERCENT') {
                  $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td class='validation' style='width:15%'>".
                  "<input id='".round($row['MeasureID'])."' class='percent' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".$v['Value']."' required/><span class='validtext'>".$percent_warning_text."</span></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr>";

                }
                else {
                  $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td class='validation' style='width:15%'>".
                  "<input id='".round($row['MeasureID'])."' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".$v['Value']."' required/><span class='validtext'>".$count_warning_text."</span></td><td style='width:10%' id='unit'>".$row["Unit"]."</td></tr>";
                }
              }
          }
          $grid .= "</table>";
          echo $grid;
      }
      echo "<input class='submit' type='submit' value='save' name='savesubmit'>";
  }
?>
