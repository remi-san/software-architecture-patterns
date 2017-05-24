<?php

use ControllerProvider\PokemonControllerProvider;
use ServiceProvider\ControllerServiceProvider;
use Silex\Application;

/** @var Application $app */
$app->register(new ControllerServiceProvider());

// Pokemon
$app->mount('/', new PokemonControllerProvider());


$app->error(function (\Exception $e, \Symfony\Component\HttpFoundation\Request $request, $code) use ($app) {
    return new \Symfony\Component\HttpFoundation\Response($e->getMessage() . PHP_EOL . $e->getTraceAsString(), $code);
});