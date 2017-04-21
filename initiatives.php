<?php
// CSS and JS
echo "<html><head>";
echo "<link rel='stylesheet' href='style.css' type='text/css'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
echo '<script src="javascript/jquery-3.2.0.min.js"></script>';
echo "</head><body>";

// Connection String
include('helpers/Connection.php');

// Set Constants
$quarter_list = array("01"=>1,"02"=>1,"03"=>1,"04"=>1);
$quarter = $quarter_list[date('m', time())];
//echo $quarter;
$fiscal_year = date('Y', time());
// Database tables
$performance_program_initiatives = "fulton_county.dbo.PerformanceManagement_Initiatives";


// UPDATE FUNCTION
if(isset($_POST['initsubmit'])) {
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
// Link back to Dept Measures
echo '<div id="info"><a href="index.php" id="Link">Department Measures</a>';

// Display Fiscal year and Quarter
echo '<div id="quarter"><h3>Current Quarter</h3> Q'.$quarter.'</div>';
echo '<div><h3>Current Fiscal Year</h3> FY '.$fiscal_year.'</div></div>';

$initiative_query = "SELECT * FROM ".$performance_program_initiatives;
$initiative_result = odbc_exec($conn, $initiative_query);
if(!$initiative_result) {
  echo odbc_errormsg();
}

if(isset($_POST['initiative'])) {
  echo '<script>console.log("'.$_POST['initiative'].'")</script>';
}

echo '<div class="form-style-5" style="max-width:75%">';
echo "<form id='initativeForm' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post' autocomplete='off'>";
echo "<table class='initiativesTable'><tr><th>Initative</th><th>Budget</th><th>Impact Statement</th><th>Status</th></tr>";

while($row = odbc_fetch_array($initiative_result)) {
  echo '<script>console.log("'.$row['ID'].'")</script>';
  echo "<tr>";
  echo "<td><textarea name='init_values[1]'>".$row['Initiative']."</textarea></td>";
  echo "<td><textarea type='text' name='init_values[1]'>".$row['Budget']."</textarea></td>";
  echo "<td><textarea type='text' name='init_values[1]'>".$row['ImpactStatement']."</textarea></td>";
  echo "<td><textarea type='text' name='init_values[1]'>".$row['Progress']."</textarea></td>";
  echo "</tr>";
}
echo "</table><input type='submit' class='submit' name='initsubmit' />";
echo "</form></div>";

 ?>
