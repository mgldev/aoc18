<?php

/**
 * Class Step
 */
class Step
{
    /** @var string */
    private $id;

    /** @var Step[] */
    private $preRequisites = [];

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

    public function addPreRequisite(Step $step)
    {
        $this->preRequisites[$step->getId()] = $step;
    }

    /**
     * @return Step[]
     */
    public function getPreRequisites(): array
    {
        return $this->preRequisites;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * Perform the step
     */
    public function start()
    {
        foreach ($this->getPreRequisites() as $preRequisite) {
            if (!$preRequisite->isComplete()) {
                $preRequisite->start();
            }
        }
    }

    /**
     * @return bool
     */
    public function isReady(): bool
    {
        foreach ($this->getPreRequisites() as $preRequisite) {
            if (!$preRequisite->isComplete()) {
                return false;
            }
        }

        return true;
    }
}