<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$map = array_map(function($row) {
	return str_split($row);
}, explode(PHP_EOL, trim($input)));
function resetMap() {
	global $map;
	global $input;
	$map = array_map(function($row) {
		return str_split($row);
	}, explode(PHP_EOL, trim($input)));
}
$currentObstacle = [];
$loopCheck = 0;
$foundObstacles = [];
$guardMovements = [];
$loops = 0;
$update = updateFrame(true, true);
while($update[0] && !$update[1] ) {
	$update = updateFrame(false, true);
}
resetMap();
$total = count($guardMovements);
$iteration = $total;

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
}
function printProgress($iteration) {
	global $input;
	global $total;
	$percent = round((($total - $iteration) / $total) * 10000) / 100;
	//echo "\r\033[K\033[1A\r\033[K\r";
	echo PHP_EOL."Overall Progress: {$iteration}/{$total} ({$percent})"; 
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
			if (in_array($entity, ['^','>','v', '<'])) {
				$obstacle = [$row, $column];
			}
		}
	}
	return $obstacle;
}
$originalGuardPosition = findGuard($map);
function updateObstacle() {
	global $map;
	global $currentObstacle;
	global $guardMovements;
	global $iteration;
	$chunk = array_slice($guardMovements, $iteration - 1, 1);
	$pos = end($chunk);
	$map[$pos[1]][$pos[2]] = 'O';
	return true;
}
function updateFrame($first = false, $absolute = false) {
	global $map;
	global $loopCheck;
	global $originalGuardPosition;
	global $foundObstacles;
	global $guardMovements;
	if ($first) {
		$foundObstacles = [];
		if ($absolute) {
			$guardMovements = [];
		}
	}
	$guard = [];
	$obstacles = [];
	$guardOrientations = ['^','>','v', '<'];
	$patrolling = true;
	$looped = false;
	foreach($map as $row => $columns) {
		foreach($columns as $column => $entity) {
			if (in_array($entity, $guardOrientations)) {
				$currentOrientation = array_search($entity, $guardOrientations);
				$nextOriention = $currentOrientation + 1;
				if ($nextOriention >= count($guardOrientations)) {
					$nextOriention = 0;
				}
				$headedEntity = false;
				$headedRow = $row;
				$headedColumn = $column;
				$doubleEntity = false;
				switch($currentOrientation) {
					case 0:
						$headedEntity = $map[$row - 1][$column] ?? false;
						$doubleEntity = $map[$row - 2][$column] ?? false;
						$headedRow = $row - 1;
						break;
					case 1:
						$headedEntity = $map[$row][$column + 1] ?? false;
						$doubleEntity = $map[$row][$column + 2] ?? false;
						$headedColumn = $column + 1;
						break;
					case 2:
						$headedEntity = $map[$row + 1][$column] ?? false;
						$doubleEntity = $map[$row + 2][$column] ?? false;
						$headedRow = $row + 1;
						break;
					case 3:
						$headedEntity = $map[$row][$column - 1] ?? false;
						$doubleEntity = $map[$row][$column - 2] ?? false;
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
			}
			if ($entity === '#') {
				$obstacles[] = [$row, $column];
			}
		}
	}
	//printMap($map, true);
	return [$patrolling, $looped];
}
while (updateObstacle() && $iteration >= 0) {
	$update = updateFrame(true);
	while($update[0] && !$update[1] ) {
		$update = updateFrame();
	}
	if ($update[1]) {
		//echo PHP_EOL."Looped".PHP_EOL;
		//printMap($map, false);
		$loops++;
		echo PHP_EOL."Loop Count: {$loops}".PHP_EOL;
	}
	$currentObstacle = findAdditionalObstacle($map);
	resetMap();
	$iteration--;
	printProgress($iteration);
}

echo PHP_EOL.$loops;