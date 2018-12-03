<?php

$frequencyModifiers = explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt'));
$frequencies = [];
$currentFrequency = 0;

while (true) {
    foreach ($frequencyModifiers as $frequencyModifier) {
        $currentFrequency += $frequencyModifier;
        if (array_key_exists($currentFrequency, $frequencies)) {
            die((string) $currentFrequency);
        }
        $frequencies[$currentFrequency] = true;
    }
}
