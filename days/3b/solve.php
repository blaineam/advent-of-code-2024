<?php

$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'input.txt');

function getMatches($input) {
	preg_match_all("/mul\(([0-9]{1,3}),([0-9]{1,3})\)/mi", $input, $matches);
	
	$total = 0;
	
	$left = $matches[1];
	$right = $matches[2];
	if (count($left) !== count($right)) {
		die("mismatched list count");
	}
	
	for($i=0;$i<count($left);$i++) {
		$total += ((int) $left[$i] * (int) $right[$i]);
	}
	
	return $total;
}

$parts = explode("don't()", $input);
$total = 0;
foreach($parts as $i => $part) {
	if ($i == 0) {
		$total += getMatches($part);
		continue;
	}
	if (str_contains($part, "do()")) {
		$goodParts = explode("do()", $part);
		array_splice($goodParts, 0, 1);
		$goodPart = implode("do()", $goodParts);
		$total += getMatches($goodPart);
	}
}

echo PHP_EOL.$total;