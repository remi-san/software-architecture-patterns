<?php

namespace Evaneos\Archi\Domain;

interface PokemonRepository
{
    public function add(Pokemon $pokemon);
    public function byId($id);
    public function all();
}