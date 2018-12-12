<?php

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
     * @return array
     */
    public function getMinutesAsleepArray(): array
    {
        $minutesAsleepArray = [];

        foreach ($this->sleepLog as $sleepPeriod) {
            $minutesAsleepArray[] = $sleepPeriod->toMinutesArray();
        }

        return $minutesAsleepArray;
    }
}
