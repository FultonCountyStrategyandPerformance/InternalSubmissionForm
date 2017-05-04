<?php

// The Actual Program
  // UPDATE FUNCTIONS

  // Staging Data Submission
  if(isset($_POST['savesubmit'])) {
    // Including updateFunction from Helpers folder
    include('helpers/updateFunction.php');

    // Get result of update
    list($result, $rows_updated) = updateKPI($fiscal_year, $quarter, $_SESSION['username'], $_POST['kpi_values'], $conn, $performance_program_values_staging);

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
    list($result, $rows_updated) = updateKPI($fiscal_year, $quarter, $_SESSION['username'], $_POST['kpi_values'], $conn, $performance_program_values);

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
// Initatives Link Button

//
//
// Start of the form to update the database
echo "<div class='form-style-5'>";
echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post' autocomplete='off'>";
echo "<div id='nav'><input class='submit' type='submit' value='logout' name='lgout'/>";
echo "<a class='submit' href='initiatives.php' id='Link'>Initiative Submission</a></div>";
// GENERAL INFORMATION
echo '<fieldset><legend><span class="number">1</span> General Information</legend>';

//Get the departments for that priority as a dropdown
  // The department query
  $user_department = $_SESSION['department'];
  $departments = "SELECT *
        FROM ".$performance_departments."
        WHERE DepartmentID = ".round($user_department)."
        ORDER BY DepartmentID ASC";

  $department_result = odbc_exec($conn, $departments);

  // Handle execution error
  if(!$department_result) {
    echo "ERROR GETTING DEPARTMENT RESULT";
    echo odbc_errormsg();
  }

  // Department Selection Dropdown
  echo "Departments<br />";
  echo "<select name='department' onchange='javascript: form.submit();'>";
  $menu="";

  // If the department is already selected, keep it selected
  // otherwise list the departments for the priority
  if(isset($_POST['department'])) {
      while($row = odbc_fetch_array($department_result)){
          if($row['DepartmentID'] == $_POST['department']) {
              $menu.='<option value="'.$row['DepartmentID'].'" selected="selected">'.$row['Department']."</option>";
          }
          else {
              $menu.='<option value="'.$row['DepartmentID'].'">'.$row['Department']."</option>";
          }
      }
      echo $menu;
      echo "</select><br>";
  }
  else {
      while($row = odbc_fetch_array($department_result)){
          $menu.='<option value="'.$row['DepartmentID'].'">'.$row['Department']."</option>";
      }
      echo $menu;
      echo "</select><br>";
  }

  // Submitter of the form
  // Autofill with login value
  echo "Editor<br>   <input type='text' name='username' placeholder='Editor' value='".$_SESSION['username']."' readonly /><br>";


  echo '<div id="info"><div id="quarter"><h3>Current Quarter</h3> Q'.$quarter.'</div>';
  echo '<div><h3>Current Fiscal Year</h3> FY '.$fiscal_year.'</div></div>';
echo "</fieldset><br />";

echo '<fieldset><legend><span class="number">2</span>KPI Information</legend>';
// The KPI's for that department and their values
if($_SESSION["isAdmin"] == 1) {
  include('helpers/adminKPIS_ProgramBreakdown.php');
}
else {
  include('helpers/defaultKPIS_ProgramBreakdown.php');
}


// Close the fieldset, form, div, body and html
echo "</fieldset></form></div></body></html>";
?>
