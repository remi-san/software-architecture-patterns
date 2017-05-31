<?php

namespace Evaneos\Archi\Controllers;

use Assert\AssertionFailedException;
use Doctrine\DBAL\DBALException;
use Evaneos\Archi\Domain\Collection\PokemonCollection;
use Evaneos\Archi\Domain\Model\Exception\PokemonEvolvingException;
use Evaneos\Archi\Domain\Model\Pokemon;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;
use Evaneos\Archi\Domain\Service\PokemonService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PokemonController
{
    /** @var PokemonCollection */
    private $collection;

    /** @var PokemonService */
    private $pokemonService;

    /**
     * PokemonController constructor.
     *
     * @param PokemonCollection $collection
     * @param PokemonService    $service
     */
    public function __construct(
        PokemonCollection $collection,
        PokemonService $service
    ) {
        $this->collection = $collection;
        $this->pokemonService = $service;
    }

    /**
     * @return JsonResponse
     *
     * @throws DBALException
     */
    public function pokedex()
    {
        return new JsonResponse(array_map(function (Pokemon $pokemon) {
            return $pokemon->toArray();
        }, $this->collection->all()));
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws AssertionFailedException
     * @throws \InvalidArgumentException
     * @throws DBALException
     */
    public function getInformation($uuid)
    {
        $pokemon = $this->collection->get(new PokemonId($uuid));

        if ($pokemon === null) {
            return new JsonResponse(new \stdClass(), 404);
        }

        return new JsonResponse($pokemon->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws AssertionFailedException
     */
    public function capture(Request $request)
    {
        $uuid = (string) Uuid::uuid4();
        $type = $request->get('type');
        $level = (int) $request->get('level');

        $pokemon = $this->pokemonService->capture(
            new PokemonId($uuid),
            new PokemonType($type),
            new PokemonLevel($level)
        );

        return new JsonResponse($pokemon->toArray());
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws PokemonEvolvingException
     * @throws \Assert\AssertionFailedException
     */
    public function evolve($uuid)
    {
        $pokemon = $this->pokemonService->evolve(new PokemonId($uuid));

        return new JsonResponse($pokemon->toArray());
    }
}
