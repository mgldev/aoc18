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
