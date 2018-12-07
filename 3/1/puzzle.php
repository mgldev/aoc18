<?php

require_once 'Claim.php';

/** @var Claim[] $claims */
$claims = array_map(function(string $claimString) {
    return Claim::fromString($claimString);
}, explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt')));

$overlapping = 0;
$map = [];

foreach ($claims as $claim) {
    foreach ($claim->getClaimGrid() as $occupiedCoordinate) {
        if (!isset($map[$occupiedCoordinate])) {
            $map[$occupiedCoordinate] = [];
        }
        $map[$occupiedCoordinate][] = $claim->getId();
        $overlapping += (int) count($map[$occupiedCoordinate]) === 2;
    }
}

die((string) $overlapping);