<?php

/**
 * Class DependencyResolver
 */
class DependencyResolver
{
    /**
     * Right answer: OKBNLPHCSVWAIRDGUZEFMXYTJQ
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
                    $this->completeStep($step, $completedStepVisitor);
                } catch (StepDependencyException $stepDependencyException) {
                    continue;
                } catch (TriggerDependencyException $triggerDependencyException) {
                    break;
                }
            }
        }

        return (string) $completedStepVisitor;
    }

    /**
     * @param Step $step
     * @param CompletedStepVisitor $completedStepVisitor
     * @param int $depth
     */
    private function completeStep(Step $step, CompletedStepVisitor $completedStepVisitor, int &$depth = 0)
    {
        if (!$step->isComplete()) {
            try {
                $step->complete($completedStepVisitor);
                foreach ($step->getTriggers() as $trigger) {
                    $depth++;
                    $this->completeStep($trigger, $completedStepVisitor, $depth);
                }
            } catch (StepDependencyException $stepDependencyException) {
                if ($depth > 0) {
                    throw new TriggerDependencyException($stepDependencyException->getMessage());
                }
            }
        }
    }
}
