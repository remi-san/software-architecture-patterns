<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Repositories\PokemonRepository;
use Service\PokemonService;

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
        $app['application.services.pokemon'] = function () use ($app) {
            return new PokemonService(new PokemonRepository($app['db']));
        };
    }
}
