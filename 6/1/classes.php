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

    /**
     * @return array
     */
    public function toArray(): array
    {
        $steps = [];

        foreach ($this->getSteps() as $step) {
            $steps[] = $step->toArray();
        }

        return $steps;
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

    /** @var Step[] */
    private $triggers = [];

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
     * @param CompletedStepVisitor $completedStepVisitor
     */
    public function complete(CompletedStepVisitor $completedStepVisitor)
    {
        foreach ($this->getDependencies() as $dependency) {
            if (!$dependency->isComplete()) {
                throw new InvalidArgumentException("Failed to complete " . $this->getId() . " - incomplete dependencies");
            }
        }

        $this->isComplete = true;
        $completedStepVisitor->visit($this);
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
        $step->addTrigger($this);
        $this->dependencies[$step->getId()] = $step;
    }

    /**
     * @return Step[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @param Step $step
     */
    public function addTrigger(Step $step)
    {
        $this->triggers[$step->getId()] = $step;
    }

    /**
     * @return Step[]
     */
    public function getTriggers(): array
    {
        ksort($this->triggers);

        return $this->triggers;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dependencies = [];
        $triggers = [];

        foreach ($this->getDependencies() as $step) {
            $dependencies[] = $step->getId();
        }

        foreach ($this->getTriggers() as $step) {
            $triggers[] = $step->getId();
        }

        return [
            'id' => $this->getId(),
            'dependsOn' => $dependencies,
            'triggers' => $triggers,
        ];
    }
}

/**
 * Class StepCollectionBuilder
 */
class StepCollectionBuilder
{
    /**
     * @param string $filename
     *
     * @return StepCollection
     */
    public function build(string $filename): StepCollection
    {
        $collection = new StepCollection();

        foreach (file($filename, FILE_IGNORE_NEW_LINES) as $instruction) {
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

        return $collection;
    }
}

/**
 * Class CompletedStepVisitor
 */
class CompletedStepVisitor
{
    /** @var Step[] */
    private $steps = [];

    /**
     * @param Step $step
     */
    public function visit(Step $step)
    {
        $this->steps[$step->getId()] = $step;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return strtoupper(
            implode(
                array_map(
                    function(Step $step) {
                        return $step->getId();
                    },
                    $this->steps
                )
            )
        );
    }
}

/**
 * Class DependencyResolver
 */
class DependencyResolver
{
    /**
     * @param StepCollection $stepCollection
     *
     * @return string
     */
    public function resolve(StepCollection $stepCollection): string
    {
        $completedStepVisitor = new CompletedStepVisitor();

        while (!$stepCollection->isProcessed()) {
            foreach ($stepCollection->getSteps() as $step) {
                try {
                    if (!$step->isComplete()) {
                        $step->complete($completedStepVisitor);
                        foreach ($step->getTriggers() as $key => $trigger) {
                            try {
                                $trigger->complete($completedStepVisitor);

                                foreach ($trigger->getTriggers() as $key2 => $trigger2) {
                                    try {
                                        $trigger2->complete($completedStepVisitor);
                                    } catch (Exception $exception) {
                                        break 3;
                                    }
                                }

                            } catch (Exception $exception) {
                                break 2;
                            }
                        }
                    }
                } catch (Exception $exception) {
                    continue;
                }
            }
        }

        return (string) $completedStepVisitor;
    }
}