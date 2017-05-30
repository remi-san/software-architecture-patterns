<?php

namespace Evaneos\Archi\Domain;

use Evaneos\Archi\Exceptions\CannotEvolve;
use Evaneos\Archi\Exceptions\UnknownPokemonType;

class Pokemon
{
    private $id;
    private $type;
    private $level;

    public function __construct(PokemonId $id, PokemonType $type, PokemonLevel $level)
    {
        $this->id = $id;
        $this->type = $type;
        $this->level = $level;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function evolve(EvolutionProvider $provider)
    {
        $evolution = $provider->evolutionFor($this);
        if ($evolution === false) {
            throw new CannotEvolve(
                sprintf('Pokemon identified by "%s" cannot evolve.', $this->id->getId())
            );
        }
        $this->type = new PokemonType($evolution);
    }
}