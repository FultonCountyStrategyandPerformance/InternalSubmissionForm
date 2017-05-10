<?php
// CSS and JS
echo "<html><head>";
echo "<link rel='stylesheet' href='style.css' type='text/css'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
echo '<script src="javascript/jquery-3.2.0.min.js"></script>';
echo "</head><body>";

// Start the Session
session_start();

// Connection String
include('helpers/Connection.php');

// Database tables
// Staging Table
$performance_program_initiatives_staging = "fulton_county.dbo.PerformanceManagement_Initiatives_staging";
// Production Data table
$performance_program_initiatives = "fulton_county.dbo.PerformanceManagement_Initiatives";
// Start Dates
$performance_quarter_start_dates = "StartDates";

// Set Constants
include('helpers/getCurrentQuarter.php');
$quarter = getCurrentQuarter($conn, $performance_quarter_start_dates);
//echo $quarter;
$fiscal_year = date('Y', time());

// UPDATE FUNCTION
// Staging Data Submission
if(isset($_POST['savesubmit'])) {
  // Including updateFunction from Helpers folder
  include('helpers/updateInitiativeFunction.php');

  echo "<script>console.log(`".$_POST['init_values']."`)</script>";
  // Get result of update
  list($result, $rows_updated) = updateKPI($fiscal_year,
    $quarter,
    $_COOKIE['username'],
    $_POST['init_values'],
    $conn,
    $performance_program_initiatives);

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
  include('helpers/updateInitiativeFunction.php');

  echo "<script>console.log(`".$_POST['init_values']."`)</script>";
  // Get result of update
  list($result, $rows_updated) = updateKPI($fiscal_year,
    $quarter,
    $_COOKIE['username'],
    $_POST['init_values'],
    $conn,
    $performance_program_initiatives);

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

// GUI


$initiative_query = "SELECT * FROM ".$performance_program_initiatives;
$initiative_result = sqlsrv_query($conn, $initiative_query);
if(!$initiative_result) {
  echo sqlsrv_errors();
}

if(isset($_POST['initiative'])) {
  echo '<script>console.log("'.$_POST['initiative'].'")</script>';
}

echo '<div class="form-style-5" style="max-width:75%">';
echo "<form id='initativeForm' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post' autocomplete='off'>";
// Link back to Dept Measures
echo '<div id="info" style="text-align:center"><a href="index.php" id="Link">To Department Measures</a>';

// Display Fiscal year and Quarter
echo '<div id="nav" style="text-align:center"><div id="quarter"><h3>Current Quarter</h3> Q'.$quarter.'</div>';
echo '<div><h3>Current Fiscal Year</h3> FY '.$fiscal_year.'</div></div>';

// Admin Form
if($_SESSION['isAdmin'] == 1) {
  echo "<table class='initiativesTable'><tr><th>Initative</th><th>Budget</th><th>Impact Statement</th><th>Status</th></tr>";

  while($row = sqlsrv_fetch_array($initiative_result)) {
    echo '<script>console.log("'.$row['ID'].'")</script>';
    echo "<tr>";
    echo "<td><textarea name='init_values[1]'>".$row['Initiative']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['Budget']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['ImpactStatement']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['Progress']."</textarea></td>";
    echo "</tr>";
  }
  echo "</table>";
  echo "<input type='checkbox' name='valid' value='agreed' required>I verify that this data is correct<br>";
  echo "<input type='submit' class='submit' name='adminsubmit' />";
}
else {
  echo "<table class='initiativesTable'><tr><th>Initative</th><th>Budget</th><th>Impact Statement</th><th>Status</th></tr>";

  while($row = sqlsrv_fetch_array($initiative_result)) {
    echo '<script>console.log("'.$row['ID'].'")</script>';
    echo "<tr>";
    echo "<td><textarea name='init_values[1]'>".$row['Initiative']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['Budget']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['ImpactStatement']."</textarea></td>";
    echo "<td><textarea type='text' name='init_values[1]'>".$row['Progress']."</textarea></td>";
    echo "</tr>";
  }
  echo "</table><input type='submit' class='submit' value='save' name='savesubmit' />";
}
echo "</form></div>";

 ?>
