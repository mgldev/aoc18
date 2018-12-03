<?php

// day one, puzzle two
$frequencyModifiers = explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt'));

$repeatFound = false;
$frequencies = [];
$currentFrequency = 0;

while (!$repeatFound) {
    foreach ($frequencyModifiers as $frequencyModifier) {
        $currentFrequency += $frequencyModifier;
        if (in_array($currentFrequency, $frequencies)) {
            $repeatFound = true;
            break;
        }
        $frequencies[] = $currentFrequency;
    }
}

if ($repeatFound) {
    echo 'First repeating frequency is ' . $currentFrequency;
} else {
    echo 'No repeat found';
}