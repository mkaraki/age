<?php

function get_age_with_month(int $year, int $month, int $day, int|null $now = null): array|false {
    if ($now === null) {
        $now = time();
    }
    
    $target_unix_time = strtotime("$year-$month-$day");
    if ($now < $target_unix_time) {
        return false;
    }
    
    $now_year = intval(date("Y", $now));
    $now_month = intval(date("m", $now));
    $now_day = intval(date("d", $now));
    
    $elapsed_year = $now_year - $year;
    if ($now_month < $month || $now_day < $day) {
        $elapsed_year -= 1;
    }
    
    $elapsed_month = $now_month - $month;
    if ($now_day < $day) {
        $elapsed_month -= 1;
    }
    if ($elapsed_month < 0) {
        $elapsed_month = 12 + $elapsed_month;
    }
    
    return [$elapsed_year, $elapsed_month];
}