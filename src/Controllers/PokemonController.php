<?php

namespace Evaneos\Archi\Controllers;

use Service\PokemonService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PokemonController
{
    /** @var PokemonService */
    private $pokemonService;


    /**
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonService
     */
    public function __construct(PokemonService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }

    /**
     * @return JsonResponse
     */
    public function pokedex(Request $request) : JsonResponse
    {
        $pokedex = $this->pokemonService->pokedex();

        return new JsonResponse($pokedex, 200);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function getInformation(string $uuid) : JsonResponse
    {
        $pokemon = $this->pokemonService->getInformation($uuid);

        if (!$pokemon) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($pokemon->toArray(), 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function capture(Request $request) : JsonResponse
    {
        try {
            $pokemon = $this->pokemonService->capture([
                'type' => $request->get('type'),
                'level' => $request->get('level'),
            ]);

            return new JsonResponse($pokemon->toArray(), 201);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function evolve(string $uuid) : JsonResponse
    {
        if ($this->pokemonService->exists($uuid)) {
            $pokemon = $this->pokemonService->getInformation($uuid);

            if (!$pokemon) {
                return new JsonResponse([], 404);
            }

            try {
                $evolvedPokemon = $this->pokemonService->evolve($pokemon);

                return new JsonResponse($evolvedPokemon->toArray(), 200);
            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error' => $e->getMessage()
                    ],
                    400
                );
            }
        } else {
            return new JsonResponse([], 404);
        }
    }
}
