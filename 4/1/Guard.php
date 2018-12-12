<?php

/**
 * Class Guard
 */
class Guard
{
    /** @var string */
    private $id;

    /** @var SleepPeriod[] */
    private $sleepLog;

    /**
     * Guard constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->sleepLog = [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param SleepPeriod $sleepPeriod
     */
    public function recordSleepPeriod(SleepPeriod $sleepPeriod)
    {
        $this->sleepLog[] = $sleepPeriod;
    }

    /**
     * @return SleepPeriod[]
     */
    public function getSleepLog(): array
    {
        return $this->sleepLog;
    }

    /**
     * @return int
     */
    public function getMinutesAsleep(): int
    {
        $total = 0;

        foreach ($this->sleepLog as $sleepPeriod) {
            $total += $sleepPeriod->toMinutes();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getMostFrequentlyOccurringMinute(): int
    {
        $minutesAsleepArray = [];

        foreach ($this->sleepLog as $sleepPeriod) {
            foreach ($sleepPeriod->toMinutesArray() as $minute)
            $minutesAsleepArray[] = $minute;
        }

        $counts = array_count_values($minutesAsleepArray);
        arsort($counts);

        return array_keys($counts)[0];
    }

    /**
     * @return int
     */
    public function getAdventOfCodeAnswer(): int
    {
        return $this->getId() * $this->getMostFrequentlyOccurringMinute();
    }
}
