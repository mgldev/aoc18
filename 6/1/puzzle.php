<?php

/**
 * Class StepCollection
 */
class StepCollection
{
    /** @var Step[] */
    private $steps = [];

    /**
     * @param Step $step
     */
    public function addStep(Step $step)
    {
        $this->steps[$step->getId()] = $step;
    }

    /**
     * @param string $stepId
     *
     * @return null|Step
     */
    public function getStepById(string $stepId)
    {
        return $this->steps[$stepId] ?? null;
    }

    /**
     * @return Step[]
     */
    public function getSteps(): array
    {
        ksort($this->steps);

        return $this->steps;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        foreach ($this->getSteps() as $step) {
            if (!$step->isComplete()) {
                return false;
            }
        }

        return true;
    }
}

/**
 * Class Step
 */
class Step
{
    /** @var string */
    private $id;

    /** @var Step[] */
    private $dependencies = [];

    /** @var bool */
    private $isComplete = false;

    /**
     * Step constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Step $step
     */
    public function addDependency(Step $step)
    {
        $this->dependencies[$step->getId()] = $step;
    }

    /**
     * @return Step[]
     */
    public function getDependencies(): array
    {
        ksort($this->dependencies);

        return $this->dependencies;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * @return Step|null
     */
    public function getNextIncompleteDependency()
    {
        foreach ($this->getDependencies() as $dependency) {
            if (!$dependency->isComplete()) {
                return $dependency;
            }
        }

        return null;
    }

    /**
     * Perform the step
     */
    public function complete()
    {
        $this->isComplete = true;
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return count($this->getDependencies()) > 0;
    }
}

$collection = new StepCollection();

foreach (file(__DIR__ . '/../input.txt', FILE_IGNORE_NEW_LINES) as $instruction) {
    $result = explode('step ', strtolower($instruction));
    $dependantId = substr($result[1], 0, 1);
    $id = substr($result[2], 0, 1);

    if (($step = $collection->getStepById($id)) === null) {
        $step = new Step($id);
        $collection->addStep($step);
    }

    if (($dependentStep = $collection->getStepById($dependantId)) === null) {
        $dependentStep = new Step($dependantId);
        $collection->addStep($dependentStep);
    }

    $step->addDependency($dependentStep);
}

$stop = true;

while (!$collection->isProcessed()) {
    foreach ($collection->getSteps() as $step) {
        if (!$step->isComplete()) {
            attemptStepCompletion($step);
        }
    }
}

function attemptStepCompletion(Step $step)
{
    $nextIncompleteStep = $step->getNextIncompleteDependency();

    if (is_null($nextIncompleteStep)) {
        $step->complete();
        echo $step->getId();
    } else {
        foreach ($step->getDependencies() as $dependency) {
            if (!$dependency->isComplete()) {
                attemptStepCompletion($dependency);
            }
        }
    }
}
