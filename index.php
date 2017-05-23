<?php
// CSS and JS
echo "<html><head>";
echo "<link rel='stylesheet' href='style.css' type='text/css'>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
echo '<script src="javascript/jquery-3.2.0.min.js"></script>';
echo "</head><body>";

// TURN OFF ERRORS AFTER DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 'on');
date_default_timezone_set("America/New_York");
// Constants
$percent_warning_text = "Warn: Percent over 100";
$count_warning_text = "Warn: Number significantly different from the average";
// Database tables
// STAGING TABLES FOR USER INPUT
$performance_program_values_staging = "edtables.dbo.PerformanceManagement_ProgramValues_staging";

// ADMIN TABLES FOR FINAL SUBMISSION
$performance_program_values = "edtables.dbo.PerformanceManagement_ProgramValues";

// ADDITIONAL TABLES
$performance_departments = "edtables.dbo.PerformanceManagement_Departments";
$performance_program_kpis = "edtables.dbo.PerformanceManagement_ProgramKPIs";
$performance_quarter_start_dates = "edtables.dbo.PerformanceManagement_StartDates";
$users_table = "edtables.dbo.PerformanceManagement_Users";

// Include the connection string parameters
// returns the $conn variable that is the
// database connection
include('helpers/Connection.php');

// Timelines
include('helpers/getCurrentQuarter.php');
$curr_quarter = getCurrentQuarter($conn, $performance_quarter_start_dates);

$curr_year = date('Y', time());
//echo $fiscal_year;
$kpi_values = array();

session_start();

// LOGOUT
if(isset($_POST['lgout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  header('HTTP/1.1 200 OK');
}

// Show login screen if no user is set
if(!isset($_POST['user']) AND !isset($_SESSION["username"])){
  include('pages/login.php');
}
else {
  include('helpers/loginValidation.php');
  if(isset($_SESSION['username']))
    list($login, $user, $password, $department,$_SESSION["isAdmin"]) = validate($conn, $_SESSION['username'], $_SESSION['password'],$users_table);
  else if (isset($_POST['user'])){
    list($login, $user, $password, $department,$_SESSION["isAdmin"]) = validate($conn, $_POST['user'], $_POST['password'],$users_table);
  }

  if(!$login) {
    echo $user." is not a valid user or password incorrect.";
    echo "<br><button onclick='history.go(-1);'>back</button>";
  }
  else {
    $_SESSION['username'] = $user;
    $_SESSION['department'] = $department;
    $_SESSION['password'] = $password;

    // Redirect user to KPI after login
    include('kpis.php');
  }
}
 ?>
