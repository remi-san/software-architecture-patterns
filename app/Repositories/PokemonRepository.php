<?php

namespace Repositories;

use DAL\Pokemon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class PokemonRepository
{
    /**
     * @var Connection
     */
    private $connection;


    /**
     * Constructor.
     *
     * @param  Connection $connection
     *
     * @return void
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Finds all the pokemons.
     *
     * @return array
     */
    public function findAll() : array
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection';
        $query = $this->connection->query($sql);

        return $query->fetchAll();
    }

    /**
     * Finds one pokemon.
     *
     * @param  string $uuid
     *
     * @return mixed
     */
    public function findOne(string $uuid)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);

        try {
            $query->execute();

            return $query->fetch();
        } catch (DBALException $e) {
            return false;
        }
    }

    /**
     * Captures a pokemon.
     *
     * @param  Pokemon $pokemon
     *
     * @return bool
     */
    public function capture(Pokemon $pokemon) : bool
    {
        $sql = 'INSERT INTO pokemon.collection (uuid, type, level) VALUES (:uuid, :type, :level)';
        $query = $this->connection->prepare($sql);

        $query->bindValue('uuid', $pokemon->getId());
        $query->bindValue('type', $pokemon->getType());
        $query->bindValue('level', $pokemon->getLevel());

        return $query->execute();
    }

    /**
     * Checks if a pokemon exists.
     *
     * @param  string $uuid
     *
     * @return bool
     */
    public function exists(string $uuid) : bool
    {
        $sql = 'SELECT COUNT(uuid) FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);

        $query->bindValue('uuid', $uuid);
        $query->execute();

        $result = $query->fetch();

        return $result['count'] > 0 ? true : false;
    }

    /**
     * Evolves a pokemon.
     *
     * @param  Pokemon $pokemon
     *
     * @return bool
     */
    public function evolve(Pokemon $pokemon) : bool
    {
        $sql = 'UPDATE pokemon.collection SET level = level + 1 WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);

        $query->bindValue('uuid', $pokemon->getId());

        return $query->execute();
    }
}
