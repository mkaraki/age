<?php
require_once __DIR__ . '/libage.php';

function simple_display($y, $m, $d) {
    header('Content-Type: text/plain');

    $now = time();

    echo 'Calculated at: ' . gmdate('Y-m-d H:i:s', $now) . " (UTC)\n";
    
    $age = false;

    $original_tz = date_default_timezone_get();
    date_default_timezone_set('Etc/UTC');
    try {
        $age = get_age_with_month($y, $m, $d, $now);
    } finally {
        date_default_timezone_set($original_tz);
    }
    
    if ($age === false) {
        die('Calculation error');
    }
    
    echo 'Exact age is ' . $age[0] . ' years and ' . $age[1] . " months.\n";
}
