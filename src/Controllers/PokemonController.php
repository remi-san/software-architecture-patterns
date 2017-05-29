<?php

namespace Evaneos\Archi\Controllers;

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
        return new JsonResponse([$this->service->pokedex()]);
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
        $pokemon = [
            'uuid' => $uuid,
            'type' => $type,
            'level' => $level,
        ];

        try {
            $this->service->capture($pokemon);
        } catch (\DomainException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse($pokemon);
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

        return new JsonResponse($evolvedPokemon);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    private function createErrorResponse($message)
    {
        return new JsonResponse(['error' => $message], 400);
    }
}
