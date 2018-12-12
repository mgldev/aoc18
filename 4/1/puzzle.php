<?php

require_once 'LogEntry.php';
require_once 'Guard.php';
require_once 'GuardCollection.php';
require_once 'SleepPeriod.php';

/** @var LogEntry[] $logEntries */
$logEntries = array_map(function(string $logEntryString) {
    return LogEntry::fromString($logEntryString);
}, file(__DIR__ . '/../input.txt', FILE_IGNORE_NEW_LINES));

usort($logEntries, function(LogEntry $a, LogEntry $b) {
    return $a->getDateTime() <=> $b->getDateTime();
});

$lastGuard = null;
$fellAsleepAt = null;
$guardCollection = new GuardCollection();

foreach ($logEntries as $logEntry) {
    if ($logEntry->contains('begins shift')) {
        if ($lastGuard instanceof Guard) {
            $guards[] = $lastGuard;
        }
        $matches = [];
        preg_match('/Guard #(\d+)/', $logEntry->getEvent(), $matches);
        $lastGuard = $guardCollection->getById($matches[1]);
        if (!$lastGuard instanceof Guard) {
            $lastGuard = new Guard($matches[1]);
        }
        $guardCollection->addGuard($lastGuard);
    }

    if ($logEntry->contains('falls asleep')) {
        $fellAsleepAt = $logEntry->getDateTime();
    }

    if ($logEntry->contains('wakes up')) {
        $lastGuard->recordSleepPeriod(new SleepPeriod($fellAsleepAt, $logEntry->getDateTime()));
    }
}

$longestSleeper = null;

foreach ($guardCollection->all() as $guard) {
    if (
        is_null($longestSleeper) ||
        $longestSleeper instanceof Guard && $guard->getMinutesAsleep() > $longestSleeper->getMinutesAsleep()
    ) {
        $longestSleeper = $guard;
    }
}

var_dump($longestSleeper);
var_dump($longestSleeper->getSleepLog());
var_dump($longestSleeper->getMinutesAsleep());
var_dump($longestSleeper->getMinutesAsleepArray());