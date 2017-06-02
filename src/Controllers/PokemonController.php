<?php

namespace Evaneos\Archi\Controllers;

use Assert\AssertionFailedException;
use Doctrine\DBAL\DBALException;
use Evaneos\Archi\Domain\Model\Exception\PokemonEvolvingException;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;
use Evaneos\Archi\Domain\Service\PokemonService;
use Evaneos\Archi\Query\Finder\PokemonFinder;
use Evaneos\Archi\Query\Model\QueryPokemon;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PokemonController
{
    /** @var PokemonFinder */
    private $finder;

    /** @var PokemonService */
    private $pokemonService;

    /**
     * PokemonController constructor.
     *
     * @param PokemonFinder  $finder
     * @param PokemonService $service
     */
    public function __construct(
        PokemonFinder $finder,
        PokemonService $service
    ) {
        $this->finder = $finder;
        $this->pokemonService = $service;
    }

    /**
     * @return JsonResponse
     *
     * @throws DBALException
     */
    public function listCollection()
    {
        return new JsonResponse(array_map(function (QueryPokemon $pokemon) {
            return $pokemon->toArray();
        }, $this->finder->all()));
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
        $pokemon = $this->finder->get(new PokemonId($uuid));

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

        $this->pokemonService->capture(
            new PokemonId($uuid),
            new PokemonType($type),
            new PokemonLevel($level)
        );

        return new JsonResponse($this->finder->get($uuid)->toArray());
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
        $this->pokemonService->evolve(new PokemonId($uuid));

        return new JsonResponse($this->finder->get($uuid)->toArray());
    }
}
