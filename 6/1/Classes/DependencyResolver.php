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
                    if (!$step->isComplete()) {
                        $step->complete($completedStepVisitor);
                        foreach ($step->getTriggers() as $key => $trigger) {
                            try {
                                $trigger->complete($completedStepVisitor);

                                foreach ($trigger->getTriggers() as $key2 => $trigger2) {
                                    $trigger2->complete($completedStepVisitor);
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
