<?php

namespace Evaneos\Archi\Infrastructure;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Evaneos\Archi\Domain\Collection\PokemonCollection;
use Evaneos\Archi\Domain\Model\Pokemon;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;

class SqlPokemonCollection implements PokemonCollection
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
     * @param PokemonId $id
     *
     * @return Pokemon
     *
     * @throws DBALException
     * @throws AssertionFailedException
     */
    public function get(PokemonId $id)
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
     * @param Pokemon $pokemon
     *
     * @return void
     * @throws DBALException
     */
    public function add(Pokemon $pokemon)
    {
        $sql = 'INSERT INTO pokemon.collection (uuid, type, level) VALUES (:uuid, :type, :level)';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', (string) $pokemon->getId());
        $query->bindValue('type', (string) $pokemon->getType());
        $query->bindValue('level', $pokemon->getLevel()->toInt());
        $query->execute();
    }

    /**
     * @param Pokemon $pokemon
     *
     * @return void
     * @throws DBALException
     */
    public function update(Pokemon $pokemon)
    {
        $sql = 'UPDATE pokemon.collection SET type = :type, level = :level WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', (string) $pokemon->getId());
        $query->bindValue('type', (string) $pokemon->getType());
        $query->bindValue('level', $pokemon->getLevel()->toInt());
        $query->execute();
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
            new PokemonId($pokemonArray['uuid']),
            new PokemonType($pokemonArray['type']),
            new PokemonLevel($pokemonArray['level'])
        );
    }
}
