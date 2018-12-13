<?php

/**
 * Class MostFrequentMinuteSummary
 */
class MostFrequentMinuteSummary
{
    /** @var int */
    private $minute;

    /** @var int */
    private $occurances;

    /**
     * MostFrequencySummary constructor.
     * @param int $minute
     * @param int $occurances
     */
    public function __construct(int $minute, int $occurances)
    {
        $this->minute = $minute;
        $this->occurances = $occurances;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @return int
     */
    public function getOccurances(): int
    {
        return $this->occurances;
    }
}
