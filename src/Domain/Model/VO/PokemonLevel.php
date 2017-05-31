<?php

namespace Evaneos\Archi\Domain\Model\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;

class PokemonLevel
{
    /** @var int */
    private $level;

    /**
     * PokemonLevel constructor.
     *
     * @param int $level
     *
     * @throws AssertionFailedException
     */
    public function __construct($level)
    {
        Assertion::integer($level, 'Level must be an integer');
        Assertion::greaterOrEqualThan($level, 1, 'Level must be at least 1');
        Assertion::lessOrEqualThan($level, 30, 'Max level is 30');

        $this->level = $level;
    }

    /**
     * @param PokemonLevel $minimumLevel
     *
     * @return bool
     */
    public function greaterThanOrEqual(PokemonLevel $minimumLevel)
    {
        return $this->level >= $minimumLevel->level;
    }

    /**
     * @return int
     */
    public function toInt()
    {
        return $this->level;
    }
}
