<?php

class SleepPeriod
{
    /** @var DateTime */
    private $from;

    /** @var DateTime */
    private $to;

    public function __construct(DateTime $from, DateTime $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return DateTime
     */
    public function getFrom(): DateTime
    {
        return $this->from;
    }

    /**
     * @return DateTime
     */
    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function toMinutesArray(): array
    {
        $minutes = [];

        foreach (new DatePeriod($this->getFrom(), new DateInterval('PT1M'), $this->getTo()) as $dateTime) {
            $minutes[] = $dateTime->format('i');
        }

        return $minutes;
    }

    /**
     * @return int
     */
    public function toMinutes(): int
    {
        return count($this->toMinutesArray());
    }
}
