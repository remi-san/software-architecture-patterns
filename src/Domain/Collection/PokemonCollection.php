<?php

namespace Evaneos\Archi\Domain\Collection;

use Evaneos\Archi\Domain\Model\Pokemon;
use Evaneos\Archi\Domain\Model\VO\PokemonId;

interface PokemonCollection
{
    /**
     * @param PokemonId $id
     *
     * @return Pokemon
     */
    public function get(PokemonId $id);

    /**
     * @param Pokemon $pokemon
     *
     * @return void
     */
    public function add(Pokemon $pokemon);

    /**
     * @param Pokemon $pokemon
     *
     * @return void
     */
    public function update(Pokemon $pokemon);
}
