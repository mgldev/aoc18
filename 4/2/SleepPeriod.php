<?php

/**
 * Class SleepPeriod
 */
class SleepPeriod
{
    /** @var DateTime */
    private $from;

    /** @var DateTime */
    private $to;

    /**
     * SleepPeriod constructor.
     *
     * @param DateTime $from
     * @param DateTime $to
     */
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

    /**
     * @return array
     * @throws Exception
     */
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '[%s] to [%s]: %s minutes',
            $this->getFrom()->format('Y-m-d H:i'),
            $this->getTo()->format('Y-m-d H:i'),
            $this->toMinutes()
        );
    }
}
