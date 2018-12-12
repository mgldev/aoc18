<?php

/**
 * Class GuardBuilder
 */
class GuardBuilder
{
    /**
     * @param string $sleepLogFilename
     *
     * @return GuardCollection
     */
    public function build(string $sleepLogFilename): GuardCollection
    {
        $guardCollection = new GuardCollection();

        /** @var LogEntry[] $logEntries */
        $logEntries = array_map(function(string $logEntryString) {
            return LogEntry::fromString($logEntryString);
        }, file($sleepLogFilename, FILE_IGNORE_NEW_LINES));

        usort($logEntries, function(LogEntry $a, LogEntry $b) {
            return $a->getDateTime() <=> $b->getDateTime();
        });

        $lastGuard = null;
        $fellAsleepAt = null;

        foreach ($logEntries as $logEntry) {
            if ($logEntry->contains('begins shift')) {
                if ($lastGuard instanceof Guard) {
                    $guards[] = $lastGuard;
                }
                $matches = [];
                preg_match('/Guard #(\d+)/', $logEntry->getEvent(), $matches);
                $lastGuard = $guardCollection->getById($matches[1]);
                if (!$lastGuard instanceof Guard) {
                    $lastGuard = new Guard($matches[1]);
                }
                $guardCollection->addGuard($lastGuard);
            }

            if ($logEntry->contains('falls asleep')) {
                $fellAsleepAt = $logEntry->getDateTime();
            }

            if ($logEntry->contains('wakes up')) {
                $lastGuard->recordSleepPeriod(new SleepPeriod($fellAsleepAt, $logEntry->getDateTime()));
            }
        }

        return $guardCollection;
    }
}
