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

echo PHP_EOL.getMatches($input);