<?php

namespace Evaneos\Archi\Infrastructure;

use Evaneos\Archi\DAO\PokemonDao;
use Evaneos\Archi\Domain\PokemonRepository;
use Evaneos\Archi\Domain\Pokemon;
use Evaneos\Archi\Domain\PokemonId;
use Evaneos\Archi\Domain\PokemonLevel;
use Evaneos\Archi\Domain\PokemonType;

class DBPokemonRepository implements PokemonRepository
{
    public $dao;

    public function __construct(PokemonDao $dao)
    {
        $this->dao = $dao;
    }

    public function add(Pokemon $pokemon)
    {
        $data = [
            'uuid' => $pokemon->getId()->getId(),
            'type' => $pokemon->getType()->getType(),
            'level' => $pokemon->getLevel()->getLevel(),
        ];
        if ($this->dao->byId($pokemon->getId()->getId())) {
            $this->dao->update($data);
        } else {
            $this->dao->insert($data);
        }
    }

    public function byId($id)
    {
        $data = $this->dao->byId($id);

        return $this->dataToPokemon($data);
    }

    public function all()
    {
        $collection = [];
        foreach ($this->dao->all() as $data) {
            $collection[] = $this->dataToPokemon($data);
        }

        return $collection;
    }

    private function dataToPokemon(array $data)
    {
        return new Pokemon(
            new PokemonId($data['uuid']),
            new PokemonType($data['type']),
            new PokemonLevel($data['level'])
        );
    }
}