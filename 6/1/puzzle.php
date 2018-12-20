<?php

require_once __DIR__ . '/classes.php';

$stepCollection = (new StepCollectionBuilder())->build(__DIR__ . '/../input.txt');
echo (new DependencyResolver())->resolve($stepCollection);