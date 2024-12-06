<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$map = array_map(function($row) {
	return str_split($row);
}, explode(PHP_EOL, trim($input)));
$originalMap = $map;
function resetMap() {
	global $map;
	global $originalMap;
	global $input;
	$map = $originalMap;
}
$currentObstacle = [];
$loopCheck = 0;
$foundObstacles = [];
$guardMovements = [];
$loops = [];
$originalGuardLocation = findGuard($originalMap);
$update = updateFrame(true, true);
while($update[0] && !$update[1] ) {
	$update = updateFrame(false, true);
}
resetMap();
$total = count($guardMovements);
$iteration = $total;
$starttime = microtime(true);
function printMap($map, $reset = true) {
	if ($reset) {
		for($i = 0; $i < count($map) + 1; $i++) {
			// Return to the beginning of the line
			echo "\r";
			// Erase to the end of the line
			echo "\033[K";
			// Move cursor Up a line
			echo "\033[1A";
			// Return to the beginning of the line
			echo "\r";
			// Erase to the end of the line
			echo "\033[K";
			// Return to the beginning of the line
			echo "\r";
			// Can be consolodated into
			// echo "\r\033[K\033[1A\r\033[K\r";
		}
	} else {
		echo PHP_EOL.PHP_EOL;
	}
	foreach($map as $columns) {
		foreach($columns as $entity) {
			echo $entity;
		}
		echo PHP_EOL;
	}
	usleep(1000);
}
function printProgress($iteration) {
	global $input;
	global $total;
	global $loops;
	global $starttime;
	if ($iteration % 50 === 0) {
		$endtime = microtime(true);
		$s = $endtime - $starttime;
		$h = floor($s / 3600);
		$s -= $h * 3600;
		$m = floor($s / 60);
		$s -= $m * 60;
		$hrt = $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
		$percent = round((($total - $iteration) / $total) * 10000) / 100;
		$loopCount = count($loops);
		//echo "\r\033[K\033[1A\r\033[K\r";
		echo PHP_EOL."{$hrt} ||| Overall Progress: {$iteration}/{$total} ({$percent}) = [{$loopCount}]"; 
	}
}
function countPatrolMovements($map) {
	$patrols = 0;
	foreach($map as $columns) {
		foreach($columns as $entity) {
			if ($entity === 'X') {
				$patrols++;
			}
		}
	}
	return $patrols;
}
function findAdditionalObstacle($map) {
	$obstacle = [];
	foreach($map as $row => $columns) {
		foreach($columns as $column => $entity) {
			if ($entity === 'O') {
				$obstacle = [$row, $column];
			}
		}
	}
	return $obstacle;
}
function findGuard($map) {
	$obstacle = [];
	foreach($map as $row => $columns) {
		foreach($columns as $column => $entity) {
			$guardOrientations = ['^','>','v', '<'];
			if (in_array($entity, $guardOrientations)) {
				$currentOrientation = array_search($entity, $guardOrientations);
				$obstacle = [$guardOrientations[$currentOrientation], $row, $column];
			}
		}
	}
	return $obstacle;
}
function updateObstacle($absolute = false) {
	global $map;
	global $guardMovements;
	global $iteration;
	global $originalGuardLocation;
	if ($iteration < 0) {
		return false;
	}
	$chunk = array_slice($guardMovements, $iteration, 1);
	$pos = end($chunk);
	if ($originalGuardLocation === $pos) {
		return false;
	}
	if (!$absolute && $pos === false) {
		return false;
	}
	
	if ($pos !== false) {
		$map[$pos[1]][$pos[2]] = 'O';
	}
	
	return true;
}
function updateFrame($first = false, $absolute = false) {
	global $map;
	global $foundObstacles;
	global $guardMovements;
	if ($first) {
		$foundObstacles = [];
		if ($absolute) {
			$guardMovements = [];
		}
	}
	$guardOrientations = ['^','>','v', '<'];
	$patrolling = true;
	$looped = false;
	$guardExists = false;
	foreach($map as $row => $columns) {
		foreach($columns as $column => $entity) {
			if (in_array($entity, $guardOrientations)) {
				$guardExists = true;
				$currentOrientation = array_search($entity, $guardOrientations);
				$nextOriention = $currentOrientation + 1;
				if ($nextOriention >= count($guardOrientations)) {
					$nextOriention = 0;
				}
				$headedEntity = false;
				$headedRow = $row;
				$headedColumn = $column;
				switch($currentOrientation) {
					case 0:
						$headedEntity = $map[$row - 1][$column] ?? false;
						$headedRow = $row - 1;
						break;
					case 1:
						$headedEntity = $map[$row][$column + 1] ?? false;
						$headedColumn = $column + 1;
						break;
					case 2:
						$headedEntity = $map[$row + 1][$column] ?? false;
						$headedRow = $row + 1;
						break;
					case 3:
						$headedEntity = $map[$row][$column - 1] ?? false;
						$headedColumn = $column - 1;
						break;
					default:
						break;
				}
				$map[$row][$column] = 'X';
				if ($headedEntity === false) {
					$patrolling = false;
				}else if (array_key_exists("{$headedRow}-{$headedColumn}-{$currentOrientation}", $foundObstacles)) {
					$looped = true;
				}else if ($headedEntity === '#' || $headedEntity === 'O') {
					$foundObstacles["{$headedRow}-{$headedColumn}-{$currentOrientation}"] = [$headedRow, $headedColumn];
					$map[$row][$column] = $guardOrientations[$nextOriention];
				}else {
					if ($absolute) {
						$guardMovements[] = [$guardOrientations[$currentOrientation], $headedRow, $headedColumn];
					}
					$map[$headedRow][$headedColumn] = $guardOrientations[$currentOrientation];
				}
				break 2;
			}
		}
	}
	//printMap($map, true);
	return [$guardExists && $patrolling, $guardExists && $looped];
}
$newObstacle = updateObstacle(true);
while ($newObstacle && $iteration >= 0) {
	$update = updateFrame(true);
	while($update[0] && !$update[1] ) {
		$update = updateFrame();
	}
	if ($update[1]) {
		//echo PHP_EOL."Looped".PHP_EOL;
		//printMap($map, false);
		$pos = findAdditionalObstacle($map);
		$loops["{$pos[0]}-{$pos[1]}"] = 1;
		//$loopCount = count($loops);
		//echo PHP_EOL."Loop Count: {$loopCount}".PHP_EOL;
	}
	// $currentObstacle = findAdditionalObstacle($map);
	resetMap();
	$iteration--;
	printProgress($iteration);
	$newObstacle = updateObstacle();
}

echo PHP_EOL.count($loops);