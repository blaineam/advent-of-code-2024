<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$lines = explode(PHP_EOL, trim($input));
$validCalibrations = 0;
foreach($lines as $test) {
	$parts = explode(":", $test);
	$value = reset($parts);
	$nums = explode(" ", trim(end($parts)));
	$variations = [];
	foreach($nums as $numPos => $num) {		
		$prevNumPos = $numPos - 1;
		if ($numPos == 1) {
			$prevNum = $nums[$prevNumPos];
			$variations["{$prevNum}+{$num}"] = $nums[$prevNumPos] + $num;
			$variations["{$prevNumPos}*{$num}"] = $nums[$prevNumPos] * $num;
			$variations["{$prevNumPos}||{$num}"] = $nums[$prevNumPos] . $num;
		}
		if ($numPos > 1) {
			$prevNum = $nums[$prevNumPos];
			$newVariations = [];
			foreach($variations as $key => $varient) {
				$keyPos = substr_count($key, "+") + substr_count($key, "*") + substr_count($key, "||");
				if ($keyPos === $numPos - 1) {
					$newVariations["{$key}+{$num}"] = $varient + $num;
					$newVariations["{$key}*{$num}"] = $varient * $num;
					$newVariations["{$key}||{$num}"] = $varient . $num;
				}
			}
			$variations = $newVariations;
		}
	}
	$validCalibrations += in_array($value, $variations) ? $value : 0;
}
echo PHP_EOL.gmp_strval($validCalibrations);