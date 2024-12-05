<?php
$reports = [];
$lines = file(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$safe = 0;
foreach($lines as $row) {
    $report = explode(" ", $row);
    $direction = 0;
    $currentSafe = true;
    foreach($report as $i => $change) {
        if ($i == 0) {
            continue;
        }
        $incrementalChange = $report[$i-1] - $change;
        if ($incrementalChange < 0) {
            $newDirection = -1;
        } else {
            $newDirection = 1;
        }
        if ($direction == 0) {
            $direction = $newDirection;
        } else if ($direction !== $newDirection) {
            $currentSafe = false;
            break;
        }
        if (abs($incrementalChange) < 1 || abs($incrementalChange) > 3) {
            $currentSafe = false;
            break;
        }
    }
    if ($currentSafe) {
        $safe++;
    }
}

echo PHP_EOL.$safe;
