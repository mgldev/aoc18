<?php

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
