<?php

$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");

$disk = str_split(trim($input));
$expanded = [];
$fileId = 0;
foreach($disk as $pos => $file) {
	for($l=0;$l<(int)$file;$l++){
		$expanded[] = ($pos % 2 !== 0) ? '.' : $fileId;
	}
	if ($pos % 2 === 0) {
		$fileId++;
	}
}

foreach($expanded as $epos => $data) {
	if($data === '.') {
		foreach(array_reverse($expanded) as $rpos => $rdata) {
			$originalPos = count($expanded) - 1 - $rpos;
			if ($originalPos <= $epos) {
				break;
			}
			if ($rdata !== '.') {
				$expanded[$epos] = $rdata;
				$expanded[$originalPos] = '.';
				break;
			}
		}
	}
}
$checksum = 0;
foreach($expanded as $pos => $data) {
	if ($data !== '.') {
		$checksum += $pos * $data;
	}
}

echo $checksum;