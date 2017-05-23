<?php

namespace Evaneos\Archi\Controllers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PokemonController
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
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws DBALException
     */
    public function pokedex(Request $request)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection';
        $query = $this->connection->query($sql);

        return new JsonResponse([$query->fetchAll()]);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     * @throws DBALException
     */
    public function getInformation($uuid)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();

        $pokemon = $query->fetch();

        if ($pokemon === false) {
            return new JsonResponse(new \stdClass(), 404);
        }

        return new JsonResponse($pokemon);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function capture(Request $request)
    {
        $uuid = (string) Uuid::uuid4();
        $type = $request->get('type');
        $level = (int) $request->get('level');

        // TODO check type exists
        // TODO check level is in bounds

        $sql = 'INSERT INTO pokemon.collection (uuid, type, level) VALUES (:uuid, :type, :level)';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->bindValue('type', $type);
        $query->bindValue('level', $level);
        $query->execute();

        return new JsonResponse([
            'uuid' => $uuid,
            'type' => $type,
            'level' => $level
        ]);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function evolve($uuid)
    {
        // TODO

        return new JsonResponse([]);
    }
}
