<?php

$chars = str_split(file_get_contents(__DIR__ . '/../input.txt'));
$i = 0;

while ($i < count($chars)) {
    $currentChar = $chars[$i];
    if (!isset($chars[$i + 1])) {
        break;
    }
    $nextChar = $chars[$i + 1];
    $destroy = (($a = ord($currentChar) - ord($nextChar)) < 0 ? $a * -1 : $a) === 32;
    if ($destroy) {
        unset($chars[$i], $chars[$i + 1]);
        $chars = array_values($chars);
        $i -= 2;
        if ($i < 0) $i = 0;
        continue;
    }
    $i++;
}

die((string) count($chars));