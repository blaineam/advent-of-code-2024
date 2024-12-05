<?php
$reports = [];
$lines = file(__DIR__.DIRECTORY_SEPARATOR."input-nigel.txt");
$safe = 0;

function testReport($report) {
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
    return $currentSafe;
}

foreach($lines as $row) {
    $report = explode(" ", $row);
    $testReports = [$report];
    foreach($report as $i => $row) {
        $testReports[] = array_diff_key($report, [$i => true]);
    }
    foreach($testReports as $report) {
        if (testReport(array_values($report))) {
            $safe++;
            break;
        }
    }
}

echo PHP_EOL.$safe;
