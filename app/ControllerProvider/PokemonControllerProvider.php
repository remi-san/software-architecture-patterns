<?php

namespace ControllerProvider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class PokemonControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/',
            'application.controllers.pokemon:listCollection'
        );

        $controllers->get(
            '/{uuid}',
            'application.controllers.pokemon:getInformation'
        );

        $controllers->post(
            '/',
            'application.controllers.pokemon:capture'
        );

        $controllers->patch(
            '/{uuid}/evolve',
            'application.controllers.pokemon:evolve'
        );


        return $controllers;
    }
}
