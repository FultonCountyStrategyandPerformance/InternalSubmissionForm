<?php
function getCurrentQuarter($conn, $performance_quarter_start_dates) {
    $quarter_query = "SELECT Quarter, DAY(StartDate) as day, MONTH(StartDate) as month FROM ".$performance_quarter_start_dates;
    $quarter_result = sqlsrv_query($conn, $quarter_query);

    // Check for Errors
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

    $current_month = (int)date('m', time());
    $quarter = 0;

    // Iterate through and get the current quarter
    while($quarters = sqlsrv_fetch_array($quarter_result)) {
      if($current_month <= $quarters['month']) {
        $quarter = $quarters['Quarter'];
        break;
      }
      else {
        continue;
      }
    }
    return $quarter;
}

 ?>
