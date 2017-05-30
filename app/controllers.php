<?php

use ControllerProvider\PokemonControllerProvider;
use ServiceProvider\ControllerServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var Application $app */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    return new Response(nl2br($e->getMessage() . PHP_EOL . $e->getTraceAsString()), $code);
});

$app->register(new ControllerServiceProvider());

// Pokemon
$app->mount('/', new PokemonControllerProvider());
