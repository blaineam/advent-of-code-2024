<?php
$left = [];
$right = [];
$lines = file(__DIR__.DIRECTORY_SEPARATOR."input.txt");
foreach($lines as $row) {
    $parts = explode(" ", $row);
    $left[] = (int) reset($parts);
    $right[] = (int) end($parts);
}

sort($left);
sort($right);

$distance = 0;
for($i=0;$i<count($left);$i++){
    $distance += abs($left[$i] - $right[$i]);
    echo PHP_EOL.$distance;
}

