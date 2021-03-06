<?php

// The Actual Program
  // UPDATE FUNCTIONS

  // Staging Data Submission
  if(isset($_POST['savesubmit'])) {
    // Including updateFunction from Helpers folder
    include('helpers/updateFunction.php');

    // Get result of update
    list($result, $rows_updated) = updateKPI($_POST['year'], $_POST['quarter'], $_SESSION['username'], $_POST['kpi_values'],$conn, $updateConn, $performance_program_values_staging);

    // Alert users to updated rows
    if($result) {
      if($rows_updated == 0) {
        echo "<script>alert('No changes made');</script>";
      }
      else {
        echo "<script>alert('Updated ".$rows_updated." row(s) Successfully');</script>";
      }

    }
  }

  // Production Data Submission
  if(isset($_POST['adminsubmit'])) {
    // Including updateFunction from Helpers folder
    include('helpers/updateFunction.php');

    // Get result of update
    list($result, $rows_updated) = updateKPI($_POST['year'], $_POST['quarter'], $_SESSION['username'], $_POST['kpi_values'], $conn, $updateConn, $performance_program_values_staging);
    list($result, $rows_updated) = updateKPI($_POST['year'], $_POST['quarter'], $_SESSION['username'], $_POST['kpi_values'], $conn, $updateConn, $performance_program_values);
    // Alert users to updated rows
    if($result) {
      if($rows_updated == 0) {
        echo "<script>alert('No changes made');</script>";
      }
      else {
        echo "<script>alert('Updated ".$rows_updated." row(s) Successfully');</script>";
      }

    }
  }

//
echo '<h1 style="text-align:center">Departmental Performance Reporting System</h1>';
echo '<div id="img"><img src="images/logo.png" /></div>';
echo "<div class='form-style-5'>";
//
// Logout Form and link to initiatives
echo "<form action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method='post'>";
echo "<div id='nav'><input class='submit' type='submit' value='logout' name='lgout'/>";

// INITIATIVES FORM
//echo "<a class='submit' href='initiatives.php' id='Link'><div id='initiatives'>Initiative Submission <i class='fa fa-angle-double-right'></i></div></a>";

echo "</div></form>";
//
// Start of the form to update the database
echo "<form id='kpiform' name='kpiform' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post' autocomplete='off' onsubmit=''>";
// GENERAL INFORMATION
echo '<fieldset><legend><span class="number">1</span> General Information</legend>';
echo "<script>function dropdown() {
  if(window.confirm('Data entered will be lost if you have not pressed save, continue?')==true) {
    $('#kpiform').submit();
  }
  else {
    return;
  }}</script>";

//Get the departments for that priority as a dropdown
  // The department query
  $user_department = $_SESSION['department'];
  // Logic for regular users:
  if($user_department > 0) {
    $departments = "SELECT *
          FROM ".$performance_departments."
          WHERE DepartmentID = ".round($user_department)."
          ORDER BY DepartmentID ASC";
    $department_result = sqlsrv_query($conn, $departments);
    // Handle execution error
    if( ($errors = sqlsrv_errors() ) != null) {
      echo "ERROR GETTING DEPARTMENT RESULT";
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
    while($row = sqlsrv_fetch_array($department_result)) {
      $department_id = $row["DepartmentID"];
      $department_name = $row["Department"];
    }
    // Department Selection Dropdown
    echo "Departments<br />";
    echo "<select name='department'>";

    $menu = '<option value="'.$department_id.'" selected="selected">'.$department_name."</option>";
    echo $menu;
    echo "</select><br>";
  }
  if($user_department == 0) {
    $departments = "SELECT *
          FROM ".$performance_departments."
          ORDER BY DepartmentID ASC";
    $department_result = sqlsrv_query($conn, $departments);
    // Handle execution error
    if( ($errors = sqlsrv_errors() ) != null) {
      echo "ERROR GETTING DEPARTMENT RESULT";
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
    // Department Selection Dropdown
    echo "Departments<br />";
    echo "<select name='department' onchange='dropdown()'>";
    $menu = "";
    while($row = sqlsrv_fetch_array($department_result)) {
      if(isset($_POST["department"])) {
        $department_id = $_POST["department"];
        if($row['DepartmentID'] == $_POST["department"]) {
          $menu .= '<option value="'.$row["DepartmentID"].'" selected="selected">'.$row["Department"]."</option>";
        }
        else {
          $menu .= '<option value="'.$row["DepartmentID"].'">'.$row["Department"]."</option>";
        }
      }
      else {
        $menu .= '<option value="'.$row["DepartmentID"].'" selected="selected">'.$row["Department"]."</option>";
        $department_id = $row["DepartmentID"];
      }
    }
    echo $menu;
    echo "</select><br>";
  }

  // Get the Acceptable Values for count measures:
  // the average and standard deviation are used
  //include('helpers/acceptableValues.php');
  //$acceptable_values = acceptableValues($conn, $department_id, $performance_program_values_staging, $performance_program_kpis, $quarter, $fiscal_year);
  $acceptable_values = array("118"=>array("Avg"=>10,"StdDev"=>4));
  echo "<script>
  $(document).ready(function(){
    var acceptableValues = ".json_encode($acceptable_values).";
    $('input[type=number]').change(function(e){
      console.log(e);
    if(e['target']['className'] == 'percent') {
      var value = e['target']['value'];
      var measureId = e['target']['id'];
      var max = 100;
      var min = 0;
      console.log(value);
      if(value < min || value > max) {
        $('#'+measureId).hover(function(){ $('#'+measureId+'-validtext').css('visibility','visible'); });
        $('#'+measureId).css('background-color','#ff9999');
      }
      else {
        $('#'+measureId).hover(function(){ $('.validtext').css('visibility','hidden'); });
        $('#'+measureId).css('background-color','#d2d9dd');
      }
    }
    else {
      var value = e['target']['value'];
      var measureId = e['target']['id'];
      var min = parseFloat(acceptableValues[measureId]['Avg'])-parseFloat(acceptableValues[measureId]['StdDev']);
      var max = parseFloat(acceptableValues[measureId]['Avg'])+parseFloat(acceptableValues[measureId]['StdDev']);
      if(value < min || value > max) {
        $('#'+measureId).hover(function(){ $('#'+measureId+'-validtext').css('visibility','visible');});
        $('#'+measureId).css('background-color','#ff9999');
      }
      else {
        $('#'+measureId).hover(function(){ $('.validtext').css('visibility','hidden');});
        $('#'+measureId).css('background-color','#d2d9dd');
      }
    }
    });
  });</script>";
  // Submitter of the form
  // Autofill with login value
  echo "Editor<br>   <input type='text' name='username' placeholder='Editor' value='".$_SESSION['username']."' readonly /><br>";

  if($historical == 1) {
    if(isset($_POST["quarter"])) {
      $curr_quarter = $_POST["quarter"];
    }
    if(isset($_POST["year"])) {
      $curr_year = $_POST["year"];
    }
    $quarters = array(1,2,3,4);
    $fiscal_years = array(2015,2016,2017);
    echo '<div id="info"><div id="quarter"><h3>Quarter</h3>';
    echo '<select name="quarter" onchange="dropdown()">';
    foreach ($quarters as $q) {
      if($q == $curr_quarter) {
        echo '<option value='.$q.' selected>Q'.$q.'</option>';
      }
      else {
        echo '<option value='.$q.'>Q'.$q.'</option>';
      }
    }
    echo '</select></div>';
    echo '<div id="quarter"><h3>Fiscal Year</h3>';
    echo '<select name="year" onchange="dropdown()">';
    foreach ($fiscal_years as $y) {
      if($y == $curr_year) {
        echo '<option value='.$y.' selected>FY '.$y.'</option>';
      }
      else {
        echo '<option value='.$y.'>FY '.$y.'</option>';
      }
    }
    echo '</select></div></div>';
  }
  else {
    echo '<div id="info"><div id="quarter"><h3>Current Quarter</h3> Q'.$quarter.'</div>';
    echo '<div><h3>Current Fiscal Year</h3> FY '.$fiscal_year.'</div></div>';
  }
echo "</fieldset><br />";


// KPI Indicators Fieldset
echo '<fieldset><legend><span class="number">2</span>KPI Information</legend>';
// The KPI's for that department and their values
// Get the Department

if($_SESSION["isAdmin"] == 1) {
  include('pages/adminKPIS_ProgramBreakdown.php');
}
else {
  include('pages/defaultKPIS_ProgramBreakdown.php');
}


// Close the fieldset, form, div, body and html
echo "</fieldset></form></div></body></html>";
?>
