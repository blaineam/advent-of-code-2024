<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");

$map = array_map(function($row) {
	return str_split($row);
}, explode(PHP_EOL, trim($input)));

function printMap($map) {
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
	foreach($map as $columns) {
		foreach($columns as $entity) {
			echo $entity;
		}
		echo PHP_EOL;
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

function updateFrame() {
	global $map;
	$guard = [];
	$obstacles = [];
	$guardOrientations = ['^','>','v', '<'];
	$patrolling = true;
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
				}else if ($headedEntity === '#') {
					$guard = [$nextOriention, $row, $column];
					$map[$row][$column] = $guardOrientations[$nextOriention];
				} else {
					$map[$headedRow][$headedColumn] = $guardOrientations[$currentOrientation];
				}
			}
			if ($entity === '#') {
				$obstacles[] = [$row, $column];
			}
		}
	}
	
	printMap($map);
	
	return $patrolling;
}

while(updateFrame()) {
	echo PHP_EOL."Patrolling";
}

echo PHP_EOL.countPatrolMovements($map);