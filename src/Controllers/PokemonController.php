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

        if (!$this->typeExists($type)) {
            return $this->createErrorResponse(
                sprintf('Unknown type "%s".', $type)
            );
        }

        if (!$this->levelIsValid($level)) {
            return $this->createErrorResponse(
                sprintf('Level "%s" is invalid.', $level)
            );
        }

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
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
        $pokemon = $query->fetch();

        if ($pokemon === false) {
            return new JsonResponse(new \stdClass(), 404);
        }

        $evolution = $this->getEvolution($pokemon);
        if ($evolution === false) {
            return $this->createErrorResponse(
                sprintf('Pokemon %s cannot evolve.', $pokemon['uuid'])
            );
        }

        $sql = 'UPDATE pokemon.collection SET type = :type WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->bindValue('type', $evolution);
        $query->execute();

        $evolvedPokemon = array_merge($pokemon, ['type' => $evolution]);

        return new JsonResponse($evolvedPokemon);
    }

    /**
     * @param string $type
     * @return bool
     */
    private function typeExists($type)
    {
        $knownType = [
            'bulbizarre',
            'herbizarre',
            'florizarre',
        ];

        return in_array($type, $knownType);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    private function createErrorResponse($message)
    {
        return new JsonResponse(['error' => $message], 400);
    }

    /**
     * @param int $level
     * @return bool
     */
    private function levelIsValid($level)
    {
        return (1 <= $level && $level <= 30);
    }

    /**
     * @param array $pokemon
     * @return string|bool
     */
    private function getEvolution($pokemon)
    {
        $evolutionMap = [
            'bulbizarre' => [
                'min_level' => 16,
                'evolution' => 'herbizarre',
            ],
            'herbizarre' => [
                'min_level' => 32,
                'evolution' => 'florizarre',
            ],
        ];

        $type = $pokemon['type'];
        if (!isset($evolutionMap[$type])) {
            return false;
        }

        $minLevel = $evolutionMap[$type]['min_level'];
        if ($pokemon['level'] < $minLevel) {
            return false;
        }

        return $evolutionMap[$type]['evolution'];
    }
}
