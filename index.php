<?php
// CSS and JS
echo "<html><head>";
echo "<link rel='stylesheet' href='style.css' type='text/css'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
echo '<script src="javascript/jquery-3.2.0.min.js"></script>';
echo "</head><body>";

// TURN OFF ERRORS AFTER DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Constants
// Database tables
$performance_departments = "fulton_county.dbo.PerformanceManagement_Departments";
$performance_program_kpis = "fulton_county.dbo.PerformanceManagement_ProgramKPIs";
$performance_program_values = "fulton_county.dbo.PerformanceManagement_ProgramValues";

// Timelines
$quarter_list = array("01"=>1,"02"=>1,"03"=>1,"04"=>1);
$quarter = $quarter_list[date('m', time())];
//echo $quarter;
$fiscal_year = date('Y', time());
//echo $fiscal_year;
$kpi_values = array();


// Include the connection string parameters
// returns the $conn variable that is the
// database connection
include('helpers/Connection.php');

// Show login screen if no user is set
if(!isset($_COOKIE['username'])) {
  include('helpers/login.php');
}
else {
  include('helpers/loginValidation.php');
  list($login, $user, $department) = validate($conn, $_COOKIE['username']);
  if(!$login) {
    echo $user." is not a valid user.";
  }
  else {
    setcookie("username",$user,time()+60*60*7);
    setcookie("department",$department,time()+60*60*7);

    // Redirect user to KPI after login
    include('kpis.php');
  }
}
 ?>
