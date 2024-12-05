<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$parts = explode(PHP_EOL.PHP_EOL, $input);
$rules = array_filter(explode(PHP_EOL, $parts[0]));
$updates = array_filter(explode(PHP_EOL, $parts[1]));
$newRules = [];
foreach($rules as $rule) {
    $parts = explode("|", $rule);
    if (!isset($newRules[$parts[0]])) {
        $newRules[$parts[0]] = [];
    }
    $newRules[$parts[0]][] = $parts[1];
}
$rules = $newRules;
foreach($updates as $index => $update) {
    $updates[$index] = explode(",", $update);
}
$sum = 0;
function validate($update) {
    global $rules;
    foreach($update as $position => $page) {
        if(isset($rules[$page])) {
            $slice = array_slice($update, $position + 1);
            foreach($slice as $testPage) {
                if (!in_array($testPage, $rules[$page])) {
                    return [false, array_search($testPage, $update), $position];
                }
            }
        }
        if ($position > 0) {
            $slice = array_slice($update,0, $position);
            foreach($slice as $revTestPage) {
                if (array_key_exists($page, $rules) && in_array($revTestPage, $rules[$page])) {
                    return [false, array_search($revTestPage, $update), $position];
                }
            }
        }
    }
    return [true];
}
foreach($updates as $update) {
    $valid = validate($update);
    if (!$valid[0]) {
        while(!$valid[0]) {
            echo PHP_EOL."original: ".implode(",",$update);
            /* shuffle($update); */
            /* if ($currentSwapOffset == count($update)) { */

            /* } else { */
                $tmp = $update[$valid[1]];
                $update[$valid[1]] = $update[$valid[2]];
                $update[$valid[2]] = $tmp;
            echo PHP_EOL."swapped: ".implode(",",$update);
            /* } */
            /* $currentSwapOffset++; */
            $valid = validate($update);
        }
        echo PHP_EOL."update validated";
        $index = ceil(count($update) / 2) - 1;
        $value = $update[$index];
        $sum += $value;
    }
}
echo PHP_EOL.$sum;
