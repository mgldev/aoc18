<?php

$boxIds = explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt'));

$twoLetterCount = $threeLetterCount = 0;

foreach ($boxIds as $index => $boxId) {
    $counts = array_unique(array_values(array_count_values(str_split($boxId))));
    $twoLetterCount += (int) in_array(2, $counts);
    $threeLetterCount += (int) in_array(3, $counts);
}

echo $twoLetterCount * $threeLetterCount;