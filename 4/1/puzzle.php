<?php

require_once 'LogEntry.php';
require_once 'GuardBuilder.php';
require_once 'Guard.php';
require_once 'GuardCollection.php';
require_once 'SleepPeriod.php';

$filename = __DIR__ . '/../input.txt';
die((string) (new GuardBuilder)->build($filename)->getLongestSleeper()->getAdventOfCodeAnswer());