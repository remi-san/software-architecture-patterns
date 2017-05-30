<?php

namespace Evaneos\Archi\Controllers;

use Evaneos\Archi\Domain\Pokemon;
use Evaneos\Archi\Exceptions\InvalidPokemonLevel;
use Evaneos\Archi\Exceptions\UnknownPokemonType;
use Evaneos\Archi\Services\PokemonService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;

class PokemonController
{
    /** @var PokemonService */
    private $service;

    /**
     * PokemonController constructor.
     *
     * @param PokemonService $service
     */
    public function __construct(PokemonService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function pokedex(Request $request)
    {
        $collection = [];
        foreach ($this->service->pokedex() as $pokemon) {
            $collection[] = $this->pokemonToArray($pokemon);
        }
        return new JsonResponse($collection);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function getInformation($uuid)
    {
        $pokemon = $this->service->getInformation($uuid);
        if ($pokemon === false) {
            return new JsonResponse(new \stdClass(), 404);
        }

        return new JsonResponse($this->pokemonToArray($pokemon));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function capture(Request $request)
    {
        $type = $request->get('type');
        $level = (int) $request->get('level');

        try {
            $pokemon = $this->service->capture($type, $level);
        } catch (\DomainException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse($this->pokemonToArray($pokemon));
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function evolve($uuid)
    {
        try {
            $evolvedPokemon = $this->service->evolve($uuid);
        } catch (\DomainException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse($this->pokemonToArray($evolvedPokemon));
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    private function createErrorResponse($message)
    {
        return new JsonResponse(['error' => $message], 400);
    }

    private function pokemonToArray(Pokemon $pokemon)
    {
        return [
            'uuid' => $pokemon->getId()->getId(),
            'type' => $pokemon->getType()->getType(),
            'level' => $pokemon->getLevel()->getLevel(),
        ];
    }
}
