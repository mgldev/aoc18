<?php

$boxIds = explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt'));

foreach ($boxIds as $boxId) {
    foreach ($boxIds as $boxIdToCompare) {
        $lev = levenshtein($boxId, $boxIdToCompare);
        if ($lev === 1) {
            $diff = array_diff_assoc($a = str_split($boxId), $b = str_split($boxIdToCompare));
            $index = array_keys($diff)[0];
            unset($a[$index], $b[$index]);
            $answer = implode('', $a);
            die($answer);
        }
    }
}