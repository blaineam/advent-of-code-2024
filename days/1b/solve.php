<?php
$left = [];
$right = [];
$lines = file(__DIR__.DIRECTORY_SEPARATOR."input.txt");
foreach($lines as $row) {
    $parts = explode(" ", $row);
    $left[] = (int) reset($parts);
    $right[] = (int) end($parts);
}

$right = array_count_values($right);

$score = 0;
for($i=0;$i<count($left);$i++){
    if (isset($right[$left[$i]])) {
        $score += $left[$i] * $right[$left[$i]];
    }
    echo PHP_EOL.$score;
}

