<?php

namespace ServiceProvider;

use Evaneos\Archi\DAO\PokemonDAO;
use Evaneos\Archi\Infrastructure\DBPokemonRepository;
use Evaneos\Archi\Infrastructure\InMemoryEvolutionProvider;
use Evaneos\Archi\Services\PokemonService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['application.dao.pokemon'] = function () use ($app) {
            return new PokemonDAO($app['db']);
        };
        $app['application.services.pokemon'] = function () use ($app) {
            return new PokemonService(
                $app['application.repository.pokemon'],
                $app['application.pokemon_evolution_provider']
            );
        };
        $app['application.repository.pokemon'] = function () use ($app) {
            return new DBPokemonRepository($app['application.dao.pokemon']);
        };
        $app['application.pokemon_evolution_provider'] = function () use ($app) {
            return new InMemoryEvolutionProvider([
                'bulbizarre' => [
                    'min_level' => 16,
                    'evolution' => 'herbizarre',
                ],
                'herbizarre' => [
                    'min_level' => 32,
                    'evolution' => 'florizarre',
                ],
            ]);
        };
    }
}
