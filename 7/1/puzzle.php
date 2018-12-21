<?php

require_once __DIR__ . '/Classes/Step.php';
require_once __DIR__ . '/Classes/StepCollection.php';
require_once __DIR__ . '/Classes/StepCollectionBuilder.php';
require_once __DIR__ . '/Classes/CompletedStepVisitor.php';
require_once __DIR__ . '/Classes/DependencyResolver.php';
require_once __DIR__ . '/Classes/StepDependencyException.php';
require_once __DIR__ . '/Classes/TriggerDependencyException.php';

$stepCollection = (new StepCollectionBuilder)->build(__DIR__ . '/../input.txt');
echo (new DependencyResolver)->resolve($stepCollection);
