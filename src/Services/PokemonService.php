<?php

namespace Evaneos\Archi\Services;

use Evaneos\Archi\Domain\EvolutionProvider;
use Evaneos\Archi\Domain\Pokemon;
use Evaneos\Archi\Domain\PokemonId;
use Evaneos\Archi\Domain\PokemonLevel;
use Evaneos\Archi\Domain\PokemonType;
use Evaneos\Archi\Domain\PokemonRepository;
use Evaneos\Archi\Exceptions\CannotEvolve;
use Evaneos\Archi\Exceptions\InvalidPokemonLevel;
use Evaneos\Archi\Exceptions\UnknownPokemon;
use Evaneos\Archi\Exceptions\UnknownPokemonType;

class PokemonService
{
    private $repository;
    private $evolutionProvider;

    public function __construct(
        PokemonRepository $repository,
        EvolutionProvider $evolutionProvider
    ) {
        $this->repository = $repository;
        $this->evolutionProvider = $evolutionProvider;
    }

    public function pokedex()
    {
        return $this->repository->all();
    }

    public function getInformation($uuid)
    {
        return $this->repository->byId($uuid);
    }

    public function capture($type, $level)
    {
        $pokemon = new Pokemon(
            PokemonId::create(),
            new PokemonType($type),
            new PokemonLevel($level)
        );

        $this->repository->add($pokemon);

        return $pokemon;
    }

    public function evolve($uuid)
    {
        $pokemon = $this->repository->byId($uuid);

        if ($pokemon === false) {
            throw new UnknownPokemon(
                sprintf('Pokemon identified by "%s" is unkown.', $uuid)
            );
        }

        $pokemon->evolve($this->evolutionProvider);
        $this->repository->add($pokemon);

        return $pokemon;
    }
}