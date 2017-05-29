<?php

namespace Evaneos\Archi\Services;

use Evaneos\Archi\DAO\PokemonDAO;
use Evaneos\Archi\Exceptions\CannotEvolve;
use Evaneos\Archi\Exceptions\InvalidPokemonLevel;
use Evaneos\Archi\Exceptions\UnknownPokemon;
use Evaneos\Archi\Exceptions\UnknownPokemonType;

class PokemonService
{
    public function __construct(PokemonDAO $dao)
    {
        $this->dao = $dao;
    }

    public function pokedex()
    {
        return $this->dao->all();
    }

    public function getInformation($uuid)
    {
        return $this->dao->byId($uuid);
    }

    public function capture(array $pokemon)
    {
        if (!$this->typeExists($pokemon['type'])) {
            throw new UnknownPokemonType(
                sprintf('Unknown type "%s".', $pokemon['type'])
            );
        }

        if (!$this->levelIsValid($pokemon['level'])) {
            throw new InvalidPokemonLevel(
                sprintf('Level "%s" is invalid.', $pokemon['level'])
            );
        }

        $this->dao->insert([
            'uuid' => $pokemon['uuid'],
            'type' => $pokemon['type'],
            'level' => $pokemon['level'],
        ]);

        return $pokemon;
    }

    public function evolve($uuid)
    {
        $pokemon = $this->dao->byId($uuid);

        if ($pokemon === false) {
            throw new UnknownPokemon(
                sprintf('Pokemon identified by "%s" is unkown.', $uuid)
            );
        }

        $evolution = $this->getEvolution($pokemon);
        if ($evolution === false) {
            throw new CannotEvolve(
                sprintf('Pokemon identified by "%s" cannot evolve.', $uuid)
            );
        }

        $evolvedPokemon = array_merge($pokemon, ['type' => $evolution]);
        $this->dao->update($evolvedPokemon);

        return $evolvedPokemon;
    }

    /**
     * @param string $type
     * @return bool
     */
    private function typeExists($type)
    {
        $knownType = [
            'bulbizarre',
            'herbizarre',
            'florizarre',
        ];

        return in_array($type, $knownType);
    }

    /**
     * @param int $level
     * @return bool
     */
    private function levelIsValid($level)
    {
        return (1 <= $level && $level <= 30);
    }

    /**
     * @param array $pokemon
     * @return string|bool
     */
    private function getEvolution($pokemon)
    {
        $evolutionMap = [
            'bulbizarre' => [
                'min_level' => 16,
                'evolution' => 'herbizarre',
            ],
            'herbizarre' => [
                'min_level' => 32,
                'evolution' => 'florizarre',
            ],
        ];

        $type = $pokemon['type'];
        if (!isset($evolutionMap[$type])) {
            return false;
        }

        $minLevel = $evolutionMap[$type]['min_level'];
        if ($pokemon['level'] < $minLevel) {
            return false;
        }

        return $evolutionMap[$type]['evolution'];
    }


}