<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$disk = str_split(trim($input));
$expanded = [];
$fileId = 0;
foreach($disk as $pos => $file) {
	if ((int)$file === 0) {
		continue;
	}
	if ($pos % 2 === 0) {
		$expanded[] = array_fill(0, (int)$file, $fileId);
		$fileId++;
	} else {
		$expanded[] = array_fill(0, (int)$file, ".");
	}
}
function mergeDots($defragged) {
	$output = [];
	$defragged = array_values(array_filter($defragged));
	foreach($defragged as $key => $data) {
		$lastKey = key(array_slice($output, -1, 1, true));
		if($key > 0 && count($defragged[$key-1]) > 0 && $defragged[$key-1][0] === '.' && count($data) > 0 && $data[0] === '.') {
			$output[$lastKey] = [...$output[$lastKey], ...$data];
		} else {
			$output[$key] = $data;
		}
	}
	return array_filter($output);
}
$defragged = $expanded;
foreach(array_reverse($expanded) as $rpos => $rdata) {
	$moves = 0;
	$originalPos = count($defragged) - 1 - array_search($rdata, array_reverse($defragged));
	$rcontent = $rdata[0];
	$rlength = count($rdata);
	if($rcontent !== '.') {
		foreach($defragged as $epos => $data) {
			$content = $data[0];
			if ($originalPos < $epos) {
				break;
			}
			if (count($data) <= 0) {
				continue;
			}
			$length = count($data);
			if($content === '.' && $length >= $rlength) {
				// insert the block in to the position before of the free space
				array_splice( $defragged, $epos, 0, [$rdata]);
				$originalPos = count($defragged) - 1 - array_search($rdata, array_reverse($defragged));
				// trim the free space to only the amount that is remaining now
				$defragged[$epos+1] = array_values(array_slice($data,$rlength));				
				// where the original data was replace its value with free space
				$defragged[$originalPos] = array_fill(0, $rlength, ".");
				// merge free space
				$defragged = mergeDots($defragged);
				break;
			}
		}
	}	
}
$result = array();
array_walk_recursive($defragged,function($v) use (&$result){ $result[] = $v; });
$defragged = $result;
$checksum = 0;
foreach($defragged as $pos => $data) {
	if ($data !== '.') {
		$checksum += $pos * $data;
	}
}
echo PHP_EOL.$checksum;