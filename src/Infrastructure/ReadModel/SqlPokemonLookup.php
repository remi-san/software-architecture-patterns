<?php

namespace Evaneos\Archi\Infrastructure\ReadModel;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Evaneos\Archi\ReadModel\Collection\PokemonLookup;
use Evaneos\Archi\ReadModel\Model\Pokemon;

class SqlPokemonLookup implements PokemonLookup
{
    /** @var Connection */
    private $connection;

    /**
     * PokemonController constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $id
     *
     * @return Pokemon
     *
     * @throws DBALException
     * @throws AssertionFailedException
     */
    public function get($id)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $id);
        $query->execute();

        $pokemonArray = $query->fetch();

        if ($pokemonArray === false) {
            return null;
        }

        return $this->getPokemonFromSqlArray($pokemonArray);
    }

    /**
     * @return Pokemon[]
     *
     * @throws DBALException
     * @throws AssertionFailedException
     */
    public function all()
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection';
        $query = $this->connection->query($sql);

        return array_map(function (array $pokemonArray) {
            return $this->getPokemonFromSqlArray($pokemonArray);
        }, $query->fetchAll());
    }

    /**
     * @param array $pokemonArray
     *
     * @return Pokemon
     *
     * @throws AssertionFailedException
     */
    private function getPokemonFromSqlArray(array $pokemonArray)
    {
        return new Pokemon(
            $pokemonArray['uuid'],
            $pokemonArray['type'],
            $pokemonArray['level']
        );
    }
}
