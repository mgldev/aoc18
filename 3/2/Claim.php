<?php

/**
 * Class Claim
 */
class Claim
{
    /** @var string */
    private $id;

    /** @var int */
    private $left;

    /** @var int */
    private $top;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /**
     * Claim constructor.
     *
     * @param string $id
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     */
    public function __construct(string $id, int $left, int $top, int $width, int $height)
    {
        $this->id = $id;
        $this->left = $left;
        $this->top = $top;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param string $claimString
     *
     * @return Claim
     */
    public static function fromString(string $claimString): self
    {
        $matches = [];
        preg_match('/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/', $claimString, $matches);

        return new self($matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
    }

    /**
     * @param Claim $targetClaim
     *
     * @return bool
     */
    public function overlaps(Claim $targetClaim): bool
    {
        $targetClaimGrid = $targetClaim->getClaimGrid();

        foreach ($this->getClaimGrid() as $coordinate) {
            if (in_array($coordinate, $targetClaimGrid)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getClaimGrid(): array
    {
        $claimedInches = [];

        for ($x = $this->getLeft(); $x < $this->getRight(); $x++) {
            for ($y = $this->getTop(); $y < $this->getBottom(); $y++) {
                $coordinates = implode('x', [$x, $y]);
                $claimedInches[] = $coordinates;
            }
        }

        return $claimedInches;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTop(): int
    {
        return $this->top;
    }

    /**
     * @return int
     */
    public function getBottom(): int
    {
        return $this->top + $this->height;
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->left + $this->width;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
