<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use ServiceProvider\PokemonServiceProvider;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Type\PokemonIdType;
use Type\PokemonLevelType;
use Type\PokemonTypeType;

$parameters = require __DIR__ . '/../config/parameters.php';

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new DoctrineServiceProvider(), ['db.options' => $parameters['db']]);
$app->register(new DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => __DIR__.'/../cache/proxies',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'Evaneos\Archi',
                'path' => __DIR__.'/../src'
            ]
        ],
        'types' => [
            PokemonIdType::NAME => PokemonIdType::class,
            PokemonLevelType::NAME => PokemonLevelType::class,
            PokemonTypeType::NAME => PokemonTypeType::class
        ]
    ],
]);

$app->register(new PokemonServiceProvider());

return $app;
