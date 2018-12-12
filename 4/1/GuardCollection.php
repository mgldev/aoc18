<?php

/**
 * Class GuardCollection
 */
class GuardCollection
{
    /** @var Guard[] */
    private $guards = [];

    public function addGuard(Guard $guard)
    {
        $this->guards[] = $guard;
    }

    /**
     * @param string $id
     * @return Guard|null
     */
    public function getById(string $id)
    {
        return $this->guards[$id] ?? null;
    }

    /**
     * @return Guard[]
     */
    public function all(): array
    {
        return $this->guards;
    }
}
