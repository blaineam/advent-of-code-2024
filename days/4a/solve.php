<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$rows = explode(PHP_EOL, trim($input));
foreach($rows as $row_num => $row) {
    $rows[$row_num] = str_split($row);
}
$num_rows = count($rows);
$num_cols = count($rows[0]);
function diagonal($r, $c, $fw): string {
    global $rows;
    global $num_rows;
    global $num_cols;
    $output = "" . $rows[$r][$c];
    $max_r = ($num_rows-1);
    $max_c = ($num_cols-1);
//    // forward slash because we read left to right and the top leans FORWARD
    if ($fw) {
        while ($r > 0 && $c < $max_c) {
            $r--;
            $c++;
            $output .= $rows[$r][$c];
        }
    } else {
//    \\ back slash because we read left to right and the top leans BACK
        while ($r < $max_r && $c < $max_c) {
            $r++;
            $c++;
            $output .= $rows[$r][$c];
        }
    }
    return $output;
}
function getRow($r): string {
    global $rows;
    return implode("", $rows[$r]);
}
function getColumn($c): string {
    global $rows;
    $output = "";
    foreach($rows as $row) {
        $output .= $row[$c];
    }
    return $output;
}
function search() {
    global $num_rows;
    global $num_cols;
    $search = [];
    for($r=0;$r<$num_rows;$r++) {
        $str = getRow($r);
        $search[] = $str;
        $search[] = strrev($str);
    }
    for($c=0;$c<$num_cols;$c++) {
        $str = getColumn($c);
        $search[] = $str;
        $search[] = strrev($str);
    }
    for($r=0;$r<$num_rows;$r++) {
        $str = diagonal($r, 0, false);
        $search[] = $str;
        $search[] = strrev($str);
    }
    for($c=1;$c<$num_cols;$c++) {
        $str = diagonal(0, $c, false);
        $search[] = $str;
        $search[] = strrev($str);
    }
    // forward slash starting at bottom left and working up
    for($r=$num_rows-1;$r>-1;$r--) {
        $str = diagonal($r, 0, true);
        $search[] = $str;
        $search[] = strrev($str);
    }
    // forward slash starting at bottom left and working right
    for($c=1;$c<$num_cols;$c++) {
        $str = diagonal($num_rows - 1, $c, true);
        $search[] = $str;
        $search[] = strrev($str);
    }
    return $search;
}
$search = search();
$count = 0;
foreach($search as $check) {
    preg_match_all("/XMAS/", $check, $matches);
    $count += count(reset($matches));
}
echo PHP_EOL.$count;

/* $search = search(); */
/* var_dump($search); */

/* var_dump(['abcd', in_array('abcd', $search)]); */
/* var_dump(['dcba', in_array('dcba', $search)]); */
/* var_dump(['mjgd', in_array('mjgd', $search)]); */
/* var_dump(['dgjm', in_array('dgjm', $search)]); */
/* var_dump(['nkh', in_array('nkh', $search)]); */

/* var_dump($search); die(); */

/* $diag = diagonal(0,1, false); */
/* var_dump(['bgl', $diag]); */

/* $diag = diagonal(3,1, true); */
/* var_dump(['nkh', $diag]); */

/* $diag = diagonal(3,0, true); */
/* var_dump(['mjgd', $diag]); */

/* $diag = diagonal(0,0, false); */
/* var_dump(['afkp', $diag]); */

/* $diag = diagonal(2,0, true); */
/* var_dump(['ifc', $diag]); */

/* $row = getRow(3); */
/* var_dump(['mnop', $row]); */

/* $col = getColumn(2); */
/* var_dump(['cgko', $col]); */
