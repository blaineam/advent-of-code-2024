<?php
$input = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."input.txt");
$rows = explode(PHP_EOL, trim($input));
foreach($rows as $row_num => $row) {
    $rows[$row_num] = str_split($row);
}
$matches = 0;
for($r=0;$r<count($rows);$r++){
    for($c=0;$c<count($rows[0]);$c++) {
        if ($c < 1 || $r < 1 || $c > count($rows[0]) - 2 || $r > count($rows) - 2) {
            continue;
        }
        $vm = $rows[$r][$c];
        $vtl = $rows[$r-1][$c-1];
        $vbl = $rows[$r+1][$c-1];
        $vtr = $rows[$r-1][$c+1];
        $vbr = $rows[$r+1][$c+1];
        if ($vm === 'A') {
            if (($vtl === 'M' && $vbr === 'S') || ($vtl === 'S' && $vbr === 'M')) {
                if (($vtr === 'M' && $vbl === 'S') || ($vtr === 'S' && $vbl === 'M')) {
                    $matches++;
                }
            }
        }
    }
}
echo PHP_EOL.$matches;
