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
                    WHERE  Year =".$curr_year."
                    AND Quarter =".$curr_quarter."
                    AND MeasureID = ".round($row['MeasureID']);
                $r = sqlsrv_query($conn, $q);
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }
                $v = sqlsrv_fetch_array($r);
                if($row['MeasureUnit'] == 'PERCENT') {
                  $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td class='validation' style='width:100%'>";
                  // If it has a value round it, if it doesn't make it an empty string
                  if(round($v['Value']) != 0) {
                   $grid .= "<input id='".round($row['MeasureID'])."' class='percent' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".round($v['Value'])."'/><span class='validtext'>".$percent_warning_text."</span></td><td style='width:10%' id='unit'>".$row["MeasureUnit"]."</td></tr>";
                  }
                  else {
                    $grid .= "<input id='".round($row['MeasureID'])."' class='percent' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".$v['Value']."'/><span class='validtext'>".$percent_warning_text."</span></td><td style='width:10%' id='unit'>".$row["MeasureUnit"]."</td></tr>";
                  }
                }
                else {
                  $grid .= "<tr><td style='width:100%' class='tooltip'>".$row['MeasureName']."<span class='tooltiptext'>".$row['Description']."</span></td><td class='validation' style='width:100%'>";
                  // If it has a value round it, if it doesn't make it an empty string
                  if (round($v['Value']) != 0){
                    $grid.="<input id='".round($row['MeasureID'])."' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".round($v['Value'])."'/><span class='validtext'>".$count_warning_text."</span></td><td style='width:10%' id='unit'>".$row["MeasureUnit"]."</td></tr>";
                  }
                  else {
                    $grid.="<input id='".round($row['MeasureID'])."' name='kpi_values[".round($row['MeasureID'])."]' type='number' step='any' value='".$v['Value']."'/><span class='validtext'>".$count_warning_text."</span></td><td style='width:10%' id='unit'>".$row["MeasureUnit"]."</td></tr>";
                  }
                }
          }
          $grid .= "</table>";
          echo $grid;
      }
      echo "<input class='submit' type='submit' value='Save' name='savesubmit'>";
  }
?>
