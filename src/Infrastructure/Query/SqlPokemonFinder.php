<?php

namespace Evaneos\Archi\Infrastructure\Query;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Evaneos\Archi\Query\Finder\PokemonFinder;
use Evaneos\Archi\Query\Model\QueryPokemon;

class SqlPokemonFinder implements PokemonFinder
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
     * @return QueryPokemon
     *
     * @throws DBALException
     * @throws AssertionFailedException
     */
    public function get($id)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', (string) $id);
        $query->execute();

        $pokemonArray = $query->fetch();

        if ($pokemonArray === false) {
            return null;
        }

        return $this->getPokemonFromSqlArray($pokemonArray);
    }

    /**
     * @return QueryPokemon[]
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
     * @return QueryPokemon
     *
     * @throws AssertionFailedException
     */
    private function getPokemonFromSqlArray(array $pokemonArray)
    {
        return new QueryPokemon(
            $pokemonArray['uuid'],
            $pokemonArray['type'],
            $pokemonArray['level']
        );
    }
}
