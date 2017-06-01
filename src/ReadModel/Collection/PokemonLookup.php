<?php

namespace Evaneos\Archi\ReadModel\Collection;

use Evaneos\Archi\ReadModel\Model\Pokemon;

interface PokemonLookup
{
    /**
     * @param string $id
     *
     * @return Pokemon
     */
    public function get($id);

    /**
     * @return Pokemon[]
     */
    public function all();
}
