<?php

namespace Evaneos\Archi\Domain\Model;

use Assert\AssertionFailedException;
use Evaneos\Archi\Domain\Model\Exception\PokemonEvolvingException;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;

/**
 * @Entity
 * @Table(name="pokemon.collection")
 */
class Pokemon
{
    /**
     * @var PokemonId
     * @Id
     * @Column(type="pokemon-id", name="uuid")
     */
    private $id;

    /**
     * @var PokemonType
     * @Column(type="pokemon-type")
     */
    private $type;

    /**
     * @var PokemonLevel
     * @Column(type="pokemon-level")
     */
    private $level;

    /**
     * Pokemon constructor.
     *
     * @param PokemonId    $id
     * @param PokemonType  $type
     * @param PokemonLevel $level
     */
    public function __construct(
        PokemonId $id,
        PokemonType $type,
        PokemonLevel $level
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->level = $level;
    }

    /**
     * Make the pokemon evolve
     *
     * @throws PokemonEvolvingException
     * @throws AssertionFailedException
     */
    public function evolve()
    {
        $evolution = $this->type->getEvolution();

        if ($evolution === null) {
            throw new PokemonEvolvingException('No evolution for this type');
        }

        if (! $this->level->greaterThanOrEqual($evolution->getMinimumLevel())) {
            throw new PokemonEvolvingException('Not enough levels to evolve');
        }

        $this->type = $evolution;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => (string) $this->id,
            'type' => (string) $this->type,
            'level' => $this->level->toInt()
        ];
    }
}
