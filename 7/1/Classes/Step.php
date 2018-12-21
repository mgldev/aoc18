<?php

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
                throw new StepDependencyException($this->getId() . ' has unresolved dependencies');
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
