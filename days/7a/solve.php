<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$lines = explode(PHP_EOL, trim($input));
$validCalibrations = gmp_init(0, 10);
foreach($lines as $test) {
	$parts = explode(":", $test);
	$value = gmp_init(reset($parts), 10);
	$nums = explode(" ", trim(end($parts)));
	$variations = [];
	foreach($nums as $numPos => $num) {		
		$prevNumPos = $numPos - 1;
		if ($numPos == 1) {
			$prevNum = $nums[$prevNumPos];
			$variations["{$prevNum}+{$num}"] = gmp_add(gmp_init($nums[$prevNumPos], 10), gmp_init($num, 10));
			$variations["{$prevNumPos}*{$num}"] = gmp_mul(gmp_init($nums[$prevNumPos], 10), gmp_init($num, 10));
		}
		if ($numPos > 1) {
			$prevNum = $nums[$prevNumPos];
			$newVariations = [];
			foreach($variations as $key => $varient) {
				$keyPos = substr_count($key, "+") + substr_count($key, "*");
				if ($keyPos === $numPos - 1) {
					$newVariations["{$key}+{$num}"] = gmp_add(gmp_init($varient, 10), gmp_init($num, 10));
					$newVariations["{$key}*{$num}"] = gmp_mul(gmp_init($varient, 10), gmp_init($num, 10));
				}
			}
			$variations = $newVariations;
		}
	}
	$validCalibrations = gmp_add($validCalibrations, in_array($value, $variations) ? $value : 0);
}
echo PHP_EOL.gmp_strval($validCalibrations);