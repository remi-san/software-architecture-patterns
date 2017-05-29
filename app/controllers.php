<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ControllerProvider\PokemonControllerProvider;
use ServiceProvider\ControllerServiceProvider;
use Silex\Application;

/** @var Application $app */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    return new Response($e->getMessage() . PHP_EOL . $e->getTraceAsString(), $code);
});
$app->register(new ControllerServiceProvider());

// Pokemon
$app->mount('/', new PokemonControllerProvider());
