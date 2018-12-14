<?php

$content = file_get_contents(__DIR__ . '/../input.txt');
$i = 0;
$scores = [];

function react(array $chars): int
{
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

    return count($chars);
}

for ($i = 97; $i <= 122; $i++) {
    $search = [chr($i), chr($i - 32)];
    $chars = str_split(str_replace($search, '', $content));
    $scores[chr($i)] = react($chars);
}

asort($scores);
die((string) reset($scores));