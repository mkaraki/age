<?php
require_once __DIR__ . '/../../libage.php';

test('Test future time', function () {
    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)
    
    $age = get_age_with_month(2026, 04, 01, $now);
    expect($age)->toBeFalse();
});

test('Test age of Minecraft 1.7.10', function () {
    // According to: https://how-old-is-mc.today/1.7.10
    // 11y 10m 16d old
    
    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)
    
    $age = get_age_with_month(2014, 5, 14, $now);
    expect($age)->toBe([11, 10]);
});

test('Test age of Minecraft 1.12.2', function () {
    // According to: https://how-old-is-mc.today/1.12.2
    // 8y 6m 12d old

    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)

    $age = get_age_with_month(2017, 9, 18, $now);
    expect($age)->toBe([8, 6]);
});

test('Test age of Minecraft 1.19.2', function () {
    // According to: https://how-old-is-mc.today/1.19.2
    // 3y 7m 25d old

    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)

    $age = get_age_with_month(2022, 8, 5, $now);
    expect($age)->toBe([3, 7]); 
});

test('Test age of birthday\'s person', function () {
    // According to: https://www.luft.co.jp/cgi/age.php
    
    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)

    $age = get_age_with_month(2025, 3, 31, $now);
    expect($age)->toBe([1, 0]);
});

test('Test age of 0y1mo person', function () {
    // According to: https://www.luft.co.jp/cgi/age.php

    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)

    $age = get_age_with_month(2026, 2, 28, $now);
    expect($age)->toBe([0, 1]);
});

test('Test age of 0y0m1d person', function () {
    // According to: https://www.luft.co.jp/cgi/age.php

    $now = 1774943223; // 2026-03-31 07:47:03 (UTC)

    $age = get_age_with_month(2026, 3, 30, $now);
    expect($age)->toBe([0, 0]);
});

test('Test person who birthday is tomorrow', function () {
    // According to: https://www.luft.co.jp/cgi/age.php

    $now = 1774832400; // 2026-03-30 10:00:00
    
    $age = get_age_with_month(2025, 3, 31, $now);
    expect($age)->toBe([0, 11]);
});

test('Test TimeZone in not right case', function () {
    $time = 1774998000; // 2026-03-31 23:00:00 (UTC)
    
    $original_tz = date_default_timezone_get();
    date_default_timezone_set('Africa/Cairo');
    
    $age = get_age_with_month(2025, 04, 1, $time);
    expect($age)->toBe([1, 0]);
    
    date_default_timezone_set('Etc/UTC');
    
    $age = get_age_with_month(2025, 04, 1, $time);
    expect($age)->toBe([0, 11]);
    
    date_default_timezone_set($original_tz);
});