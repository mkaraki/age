<?php

$year = date('Y');
$month = date('m');
$day = date('d');
$tz = null;

$error = null;

$age = null;

function calc(): void
{
    global $year, $month, $day, $tz, $error, $age, $_GET;
    require_once __DIR__ . '/_init.php';

    if (!is_numeric($_GET['year']) || !is_numeric($_GET['month']) || !is_numeric($_GET['day'])) {
        die('Invalid input');
    }

    $year = $_GET['year'];
    $month = $_GET['month'];
    $day = $_GET['day'];
    
    $y = intval($_GET['year']);
    $m = intval($_GET['month']);
    $d = intval($_GET['day']);

    if ($y < 0 || $m < 1 || $m > 12 || $d < 1 || $d > 31) {
        $error = 'Invalid input';
        return;
    }
    
    if (!in_array($_GET['tz'], DateTimeZone::listIdentifiers(), true)) {
        $error = 'Invalid time zone';
        return;
    }
    
    $tz_ok = date_default_timezone_set($_GET['tz']);
    if (!$tz_ok) {
        $error = 'Invalid time zone';
        return;
    }
    $tz = date_default_timezone_get(); // Normalize time zone name
    
    $age = get_age_with_month($y, $m, $d);
    if ($age === false) {
        $error = 'Birthday is in the future or calculation error';
        return;
    }
    
    return;
}

if (isset($_GET['year'], $_GET['month'], $_GET['day'], $_GET['tz'])) calc();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Age Calculator</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" integrity="sha512-2bBQCjcnw658Lho4nlXJcc6WkV/UxpE/sAokbXPxQNGqmNdQrWqtw26Ns9kFF/yG792pKR1Sx8/Y1Lf1XN4GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body class="p-3">
        <div style="margin: auto; max-width: 400px;">
            <h1 class="text-center mb-3">Age Calculator</h1>
            <form method="get" action="index.php" class="mb-3">
                <div class="mb-3">
                    <label for="year" class="form-label">Your birthday</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Year" name="year" id="year" pattern="\d+" required value="<?php echo htmlspecialchars($year); ?>">
                        <span class="input-group-text">-</span>
                        <input type="text" class="form-control" placeholder="Month" name="month" id="month" pattern="(0?[1-9]|1[0-2])" required value="<?php echo htmlspecialchars($month); ?>">
                        <span class="input-group-text">-</span>
                        <input type="text" class="form-control" placeholder="Day" name="day" id="day" pattern="(0?[1-9]|(1|2)\d|3[0-1])" required value="<?php echo htmlspecialchars($day); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="tz" class="form-label">Time Zone</label>
                    <input type="text" class="form-control" id="tz" list="tz-list" name="tz" required value="<?php echo htmlspecialchars($tz ?? ''); ?>">
                </div>
                <div class="text-danger mb-3" id="error">
                    <?php if ($error !== null) : ?>
                        <?= htmlspecialchars($error); ?>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php if ($age !== null && $age !== false) : ?>
                Your exact age is <?php echo $age[0]; ?> years and <?php echo $age[1]; ?> months.
            <?php endif; ?>
        </div>
        <script>
            const tzInput = document.getElementById('tz');
            const form = tzInput.form;
            const errorDiv = document.getElementById('error');
            
            if (tzInput.value === '') {
                try {
                    tzInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
                } catch {}
            }
            
            form.addEventListener('submit', (event) => {
                if (window.tzList !== undefined && !tzList.includes(tzInput.value)) {
                    event.preventDefault();
                    errorDiv.innerText = 'Invalid time zone';
                }
            });
        </script>
        <datalist id="tz-list">
            <?php 
            $tzList = DateTimeZone::listIdentifiers();
            foreach($tzList as $tzId) : 
                ?><option value="<?php echo htmlspecialchars($tzId); ?>"></option><?php
            endforeach; ?></datalist>
        <script>
            window.onload = () => {
                console.debug('Loading time zone list...');
                window.tzList = <?= json_encode($tzList) ?>;
                console.debug('Time zone list loaded: ', window.tzList.length);
            };
        </script>
    </body>
</html>