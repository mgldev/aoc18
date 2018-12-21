<?php

class StepDependencyFailure extends RuntimeException {}
class TriggerDependencyFailure extends RuntimeException {}

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
                } catch (StepDependencyFailure $stepDependencyFailure) {
                    continue;
                } catch (TriggerDependencyFailure $triggerDependencyFailure) {
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
            } catch (InvalidArgumentException $invalidArgumentException) {
                $exceptionClass = $depth === 0 ? StepDependencyFailure::class : TriggerDependencyFailure::class;
                throw new $exceptionClass($invalidArgumentException->getMessage());
            }
        }
    }
}
