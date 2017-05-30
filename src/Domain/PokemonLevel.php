<?php

namespace Evaneos\Archi\Domain;

use Evaneos\Archi\Exceptions\InvalidPokemonLevel;

class PokemonLevel
{
    private $level;

    public function __construct($level)
    {
        $this->setLevel($level);
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function isAtLeast(PokemonLevel $level)
    {
        return $this->level >= $level->getLevel();
    }

    private function setLevel($level)
    {
        if (!$this->levelIsValid($level)) {
            throw new InvalidPokemonLevel(
                sprintf('Level "%s" is invalid.', $level)
            );
        }
        $this->level = $level;
    }

    private function levelIsValid($level)
    {
        return is_int($level) && (1 <= $level && $level <= 30);
    }
}