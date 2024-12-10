<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$map = array_map(function($row) {
	return str_split($row);
}, explode(PHP_EOL, trim($input)));
$maxPos = [count($map)-1, count($map[0])-1];
function findPeaks($row, $column, &$peaks = [], &$paths = [], $currentPath = "") : int {
	global $maxPos;
	global $map;
	$peakCount = 0;
	if ($row-1 >= 0 && (int) $map[$row-1][$column] === (int) $map[$row][$column] + 1) {
		// up
		$check = $currentPath . "|" . ($row-1) .",".($column);
		if (!in_array($check, $paths)) {
			if((int) $map[$row-1][$column] === 9) {
				if (!array_key_exists(($row-1) .",".($column), $peaks)) {
					$peakCount++;
				}
				$peaks[($row-1).",".$column] = true;
			} else {
				$peakCount += findPeaks($row-1, $column, $peaks, $paths, $check);
			}
			$paths[] = $check;
		}
	}
	if ($row+1 <= $maxPos[0] && (int) $map[$row+1][$column] === (int) $map[$row][$column] + 1) {
		// down
		$check = $currentPath . "|" . ($row+1) .",".($column);
		if (!in_array($check, $paths)) {
			if((int) $map[$row+1][$column] === 9) {
				if (!array_key_exists(($row+1) .",".($column), $peaks)) {
					$peakCount++;
				}
				$peaks[($row+1) .",".($column)] = true;
			} else {
				$peakCount += findPeaks($row+1, $column, $peaks, $paths, $check);
			}
			$paths[] = $check;
		}
	}
	if ($column-1 >= 0 && (int) $map[$row][$column-1] === (int) $map[$row][$column] + 1) {
		// left
		$check = $currentPath . "|" . ($row) .",".($column-1);
		if (!in_array($check, $paths)) {
			if((int) $map[$row][$column-1] === 9) {
				if (!array_key_exists(($row) .",".($column-1), $peaks)) {
					$peakCount++;
				}
				$peaks[($row) .",".($column-1)] = true;
			} else {
				$peakCount += findPeaks($row, $column-1, $peaks, $paths, $check);
			}
			$paths[] = $check;
		}
	}
	if ($column+1 <= $maxPos[1] && (int) $map[$row][$column+1] === (int) $map[$row][$column] + 1) {
		// right
		$check = $currentPath . "|" . ($row) .",".($column+1);
		if (!in_array($check, $paths)) {
			if((int) $map[$row][$column+1] === 9) {
				if (!array_key_exists(($row) .",".($column+1), $peaks)) {
					$peakCount++;
				}
				$peaks[($row) .",".($column+1)] = true;
			} else {
				$peakCount += findPeaks($row, $column+1, $peaks, $paths, $check);
			}
			$paths[] = $check;
		}
	}
	return $peakCount;
}
$trailheads = [];
foreach($map as $row => $columns) {
	foreach($columns as $column => $elevation) {
		if ($elevation === '0') {
			$peaks = [];
			$paths = [];
			$score = findPeaks($row, $column, $peaks, $paths);
			var_dump($paths);
			$trailheads[] = ["score" => $score, "row" => $row, "column" => $column];
		}
	}
}
echo PHP_EOL.array_sum(array_column($trailheads, "score"));