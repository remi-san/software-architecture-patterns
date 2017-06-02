<?php

namespace Evaneos\Archi\Query\Finder;

use Evaneos\Archi\Query\Model\QueryPokemon;

interface PokemonFinder
{
    /**
     * @param string $id
     *
     * @return QueryPokemon
     */
    public function get($id);

    /**
     * @return QueryPokemon[]
     */
    public function all();
}
