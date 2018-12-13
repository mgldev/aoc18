<?php

require_once 'LogEntry.php';
require_once 'GuardBuilder.php';
require_once 'Guard.php';
require_once 'GuardCollection.php';
require_once 'SleepPeriod.php';
require_once 'MostFrequentMinuteSummary.php';

$filename = __DIR__ . '/../input.txt';
$guardCollection = (new GuardBuilder)->build($filename);

$guards = $guardCollection->all();
usort($guards, function (Guard $a, Guard $b) {
    if (is_null($a->getMostFrequentMinuteSummary()) || is_null($b->getMostFrequentMinuteSummary())) {
        return -1;
    }
    return $b->getMostFrequentMinuteSummary()->getOccurances() <=> $a->getMostFrequentMinuteSummary()->getOccurances();
});

$guard = reset($guards);
die((string) $guard->getAdventOfCodeAnswer());