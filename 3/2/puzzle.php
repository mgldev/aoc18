<?php

require_once 'Claim.php';

/** @var Claim[] $claims */
$claims = [];
foreach (explode(PHP_EOL, file_get_contents(__DIR__ . '/../input.txt')) as $claimString) {
    $claim = Claim::fromString($claimString);
    $claims[$claim->getId()] = $claim;
}

$map = [];
foreach ($claims as $claim) {
    foreach ($claim->getClaimGrid() as $occupiedCoordinate) {
        if (!isset($map[$occupiedCoordinate])) {
            $map[$occupiedCoordinate] = [];
        }
        $map[$occupiedCoordinate][] = $claim->getId();
    }
}

foreach ($map as $claimIds) {
    if (count($claimIds) > 1) {
        foreach ($claimIds as $claimId) {
            unset($claims[$claimId]);
        }
    }
}

die(reset($claims)->getId());