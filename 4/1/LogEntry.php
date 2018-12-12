<?php

class LogEntry
{
    /** @var DateTime */
    private $dateTime;

    /** @var string */
    private $event;

    /**
     * LogEntry constructor.
     *
     * @param string $dateTimeString
     * @param string $event
     */
    public function __construct(string $dateTimeString, string $event)
    {
        $this->dateTime = DateTime::createFromFormat('Y-m-d H:i', $dateTimeString);
        $this->event = $event;
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $logEntry
     *
     * @return LogEntry
     */
    public static function fromString(string $logEntry): self
    {
        $dateTimeString = substr($logEntry, 1, 16);
        $event = substr($logEntry, 19, strlen($logEntry) - 18);

        return new self($dateTimeString, $event);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '[' . $this->getDateTime()->format('d/m/Y H:i') . '] - ' . $this->getEvent();
    }

    /**
     * @param string $search
     *
     * @return bool
     */
    public function contains(string $search): bool
    {
        return strstr($this->getEvent(), $search) !== false;
    }
}