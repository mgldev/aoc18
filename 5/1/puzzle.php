<?php

$chars = str_split(file_get_contents(__DIR__ . '/../input.txt'));
$i = 0;

while (isset($chars[$i + 1])) {
    $currentChar = $chars[$i];
    $nextChar = $chars[$i + 1];
    $destroy = (($a = ord($currentChar) - ord($nextChar)) < 0 ? $a * -1 : $a) === 32;
    if ($destroy) {
        unset($chars[$i], $chars[$i + 1]);
        $chars = array_values($chars);
        $i = ($i - 2 < 0 ? 0 : $i - 2);
        continue;
    }
    $i++;
}

die((string) count($chars));