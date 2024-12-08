<?php

$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");

$map = array_map(function($row) {
	return str_split($row);
}, explode(PHP_EOL, trim($input)));

$maxPos = [count($map)-1, count($map[0])-1];
$freq = [];
$antinodes = [];
foreach($map as $row => $columns){
	foreach($columns as $column => $entity) {
		if(preg_match('/[a-zA-Z0-9]/', $entity)) {
			if (!array_key_exists($entity, $freq)) {
				$freq[$entity] = [];
			}
			$freq[$entity][] = [$row, $column];
			$antinodes["{$row},{$column}"] = [$row, $column];
		}
	}
}

foreach($freq as $entity =>$nodes) {
	foreach($nodes as $node => $ant) {
		foreach($nodes as $inode => $iant) {
			if ($inode === $node) {
				continue;
			}
			$rowDist = max($iant[0], $ant[0]) - min($iant[0], $ant[0]);
			$columnDist = max($iant[1], $ant[1]) - min($iant[1], $ant[1]);
			//$dist = sqrt(pow($iant[0] - $ant[0], 2) + pow($iant[1] - $iant[1], 2));
			if ($iant[0] === $ant[0]) {
				// horizontal
				die("horizontal");
				$l = min($iant[1], $ant[1]) - $rowDist;
				if ($l >= 0) {
					$pos = [$iant[0], $l];
					$antinodes["{$pos[0]},{$pos[1]}"] = $pos;
				}
				$r = max($iant[1], $ant[1]) + $rowDist;
				if ($r <= $maxPos[1]) {
					$pos = [$iant[0], $l];
					$antinodes["{$pos[0]},{$pos[1]}"] = $pos;
				}
			} else if ($iant[1] === $ant[1]) {
				// vertical
				die("vertical");
				$u = min($iant[0], $ant[0]) - $columnDist;
				if ($u >= 0) {
					$pos = [$u, $iant[1]];
					$antinodes["{$pos[0]},{$pos[1]}"] = $pos;
				}
				$d = max($iant[0], $ant[0]) + $columnDist;
				if ($d <= $maxPos[0]) {
					$pos = [$d, $iant[1]];
					$antinodes["{$pos[0]},{$pos[1]}"] = $pos;
				}
			} else {
				if ($iant[0] > $ant[0] && $iant[1] > $ant[1] || $iant[0] < $ant[0] && $iant[1] < $ant[1]) {
					// '\' down - right || top - left
					$lpos = [min($iant[0], $ant[0]) - $rowDist, min($iant[1], $ant[1]) - $columnDist];
					while ($lpos[0] >= 0 && $lpos[1] >= 0) {
						$antinodes["{$lpos[0]},{$lpos[1]}"] = $lpos;
						$lpos = [$lpos[0] - $rowDist, $lpos[1] - $columnDist];
					}
					$rpos = [max($iant[0], $ant[0]) + $rowDist, max($iant[1], $ant[1]) + $columnDist];
					while ($rpos[0] <= $maxPos[0] && $rpos[1] <= $maxPos[1]) {
						$antinodes["{$rpos[0]},{$rpos[1]}"] = $rpos;
						$rpos = [$rpos[0] + $rowDist, $rpos[1] + $columnDist];
					}
				} else {
					// '/' down left || up right
					$lpos = [max($iant[0], $ant[0]) + $rowDist, min($iant[1], $ant[1]) - $columnDist];
					while ($lpos[0] <= $maxPos[0] && $lpos[1] >= 0) {
						$antinodes["{$lpos[0]},{$lpos[1]}"] = $lpos;
						$lpos = [$lpos[0] + $rowDist, $lpos[1] - $columnDist];
					}
					$rpos = [min($iant[0], $ant[0]) - $rowDist, max($iant[1], $ant[1]) + $columnDist];
					while ($rpos[0] >= 0 && $rpos[1] <= $maxPos[1]) {
						$antinodes["{$rpos[0]},{$rpos[1]}"] = $rpos;
						$rpos = [$rpos[0] - $rowDist, $rpos[1] + $columnDist];
					}
				}
			}
		}
	}
}


foreach($map as $row => $columns){
	foreach($columns as $column => $entity) {
		if (array_key_exists("{$row},{$column}", $antinodes) && !preg_match('/[a-zA-Z0-9]/', $entity)) {
			$map[$row][$column] = "#";
		}
	}
}


function printMap($map) {
	foreach($map as $columns) {
		foreach($columns as $entity) {
			echo $entity;
		}
		echo PHP_EOL;
	}
}


function countAntiNodes($map) {
	$patrols = 0;
	foreach($map as $columns) {
		foreach($columns as $entity) {
			if ($entity === '#') {
				$patrols++;
			}
		}
	}
	return $patrols;
}

printMap($map);

echo PHP_EOL.count($antinodes);