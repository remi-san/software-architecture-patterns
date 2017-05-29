<?php

namespace ServiceProvider;

use Evaneos\Archi\DAO\PokemonDAO;
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
            return new PokemonService($app['application.dao.pokemon']);
        };
    }
}
