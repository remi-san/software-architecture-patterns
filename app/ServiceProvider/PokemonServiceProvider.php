<?php

namespace ServiceProvider;

use Evaneos\Archi\Domain\Service\PokemonService;
use Evaneos\Archi\Infrastructure\Domain\OrmPokemonCollection;
use Evaneos\Archi\Infrastructure\ReadModel\SqlPokemonLookup;
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
        $app['pokemon.collection.sql'] = function () use ($app) {
            return new SqlPokemonLookup($app['db']);
        };

        $app['pokemon.collection.orm'] = function () use ($app) {
            return new OrmPokemonCollection($app['orm.em']);
        };

        $app['pokemon.service'] = function () use ($app) {
            return new PokemonService($app['pokemon.collection.orm']);
        };
    }
}
