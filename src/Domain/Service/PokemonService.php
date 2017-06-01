<?php

namespace Evaneos\Archi\Domain\Service;

use Assert\AssertionFailedException;
use Evaneos\Archi\Domain\Model\Exception\PokemonEvolvingException;
use Evaneos\Archi\Domain\Model\Pokemon;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;
use Evaneos\Archi\Domain\Collection\PokemonCollection;

class PokemonService
{
    /** @var PokemonCollection */
    private $collection;

    /**
     * PokemonService constructor.
     *
     * @param PokemonCollection $collection
     */
    public function __construct(PokemonCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param PokemonId    $id
     * @param PokemonType  $type
     * @param PokemonLevel $level
     *
     * @return Pokemon
     */
    public function capture(
        PokemonId $id,
        PokemonType $type,
        PokemonLevel $level
    ) {
        $pokemon = new Pokemon($id, $type, $level);

        $this->collection->add($pokemon);
    }

    /**
     * @param PokemonId $id
     *
     * @return Pokemon
     *
     * @throws PokemonEvolvingException
     * @throws AssertionFailedException
     */
    public function evolve(PokemonId $id)
    {
        $pokemon = $this->collection->get($id);

        $pokemon->evolve();

        $this->collection->update($pokemon);
    }
}
