<?php

namespace Evaneos\Archi\DAO;

use Doctrine\DBAL\Connection;

class PokemonDAO
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all()
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection';
        $query = $this->connection->query($sql);

        return $query->fetchAll();
    }

    public function byId($uuid)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();

        return $query->fetch();
    }

    public function insert(array $pokemon)
    {
        $sql = 'INSERT INTO pokemon.collection (uuid, type, level) VALUES (:uuid, :type, :level)';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $pokemon['uuid']);
        $query->bindValue('type', $pokemon['type']);
        $query->bindValue('level', $pokemon['level']);
        $query->execute();
    }

    public function update(array $pokemon)
    {
        $sql = 'UPDATE pokemon.collection SET type = :type WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $pokemon['uuid']);
        $query->bindValue('type', $pokemon['type']);
        $query->execute();
    }
}