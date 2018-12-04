<?php

/**
 * Class Claim
 */
class Claim
{
    /** @var string */
    private $id;

    /** @var int */
    private $top;

    /** @var int */
    private $left;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /**
     * Claim constructor.
     *
     * @param string $id
     * @param int $top
     * @param int $left
     * @param int $width
     * @param int $height
     */
    public function __construct(string $id, int $top, int $left, int $width, int $height)
    {
        $this->id = $id;
        $this->top = $top;
        $this->left = $left;
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
        preg_match('/#(\d{3}).*(\d+),(\d+).*(\d+)x(\d+)/', $claimString, $matches);

        return new self($matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
    }

    /**
     * Determine if this claim overlaps another claim
     *
     * @param Claim $claim
     *
     * @return bool
     */
    public function overlaps(Claim $claim): bool
    {

    }
}

$claim = Claim::fromString('#123 @ 3,2: 5x4');
die(var_dump($claim));