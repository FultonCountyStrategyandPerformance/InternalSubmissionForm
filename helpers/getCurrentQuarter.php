<?php
function getCurrentQuarter($conn, $performance_quarter_start_dates) {
    $quarter_query = "SELECT Quarter, DAY(StartDate) as day, MONTH(StartDate) as month FROM ".$performance_quarter_start_dates;
    $quarter_result = odbc_exec($conn, $quarter_query);
    $current_month = (int)date('m', time());
    $quarter = 0;

    // Iterate through and get the current quarter
    while($quarters = odbc_fetch_array($quarter_result)) {
      if($current_month < $quarters['month']) {
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
