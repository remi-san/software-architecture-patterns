<?php

namespace Evaneos\Archi\Infrastructure;

use Evaneos\Archi\Domain\EvolutionProvider;
use Evaneos\Archi\Domain\Pokemon;
use Evaneos\Archi\Domain\PokemonLevel;

class InMemoryEvolutionProvider implements EvolutionProvider
{
    private $evolutionMap;

    public function __construct(array $evolutionMap)
    {
        $this->evolutionMap = $evolutionMap;
    }

    public function evolutionFor(Pokemon $pokemon)
    {
        $type = $pokemon->getType()->getType();
        if (!isset($this->evolutionMap[$type])) {
            return false;
        }

        $minLevel = $this->evolutionMap[$type]['min_level'];
        if (!$pokemon->getLevel()->isAtLeast(new PokemonLevel($minLevel))) {
            return false;
        }

        return $this->evolutionMap[$type]['evolution'];
    }
}
