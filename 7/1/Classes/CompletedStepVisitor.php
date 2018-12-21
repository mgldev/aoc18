<?php

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
