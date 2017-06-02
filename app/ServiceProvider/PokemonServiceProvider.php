<?php

namespace ServiceProvider;

use Evaneos\Archi\Domain\Service\PokemonService;
use Evaneos\Archi\Infrastructure\Domain\OrmPokemonCollection;
use Evaneos\Archi\Infrastructure\Query\SqlPokemonFinder;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PokemonServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app['pokemon.finder'] = function () use ($app) {
            return new SqlPokemonFinder($app['db']);
        };

        $app['pokemon.collection'] = function () use ($app) {
            return new OrmPokemonCollection($app['orm.em']);
        };

        $app['pokemon.service'] = function () use ($app) {
            return new PokemonService($app['pokemon.collection']);
        };
    }
}
