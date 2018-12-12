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

        $guard = $fellAsleepAt = null;

        foreach ($logEntries as $logEntry) {
            if ($logEntry->contains('begins shift')) {
                $matches = [];
                preg_match('/Guard #(\d+)/', $logEntry->getEvent(), $matches);
                $guard = $guardCollection->getById($matches[1]);
                if (!$guard instanceof Guard) {
                    $guard = new Guard($matches[1]);
                    $guardCollection->addGuard($guard);
                }
            }

            if ($logEntry->contains('falls asleep')) {
                $fellAsleepAt = $logEntry->getDateTime();
            }

            if ($logEntry->contains('wakes up')) {
                $guard->recordSleepPeriod(new SleepPeriod($fellAsleepAt, $logEntry->getDateTime()));
            }
        }

        return $guardCollection;
    }
}
