<?php

use ControllerProvider\PokemonControllerProvider;
use ServiceProvider\ControllerServiceProvider;
use Silex\Application;

/** @var Application $app */
$app->register(new ControllerServiceProvider());

// Pokemon
$app->mount('/', new PokemonControllerProvider());
