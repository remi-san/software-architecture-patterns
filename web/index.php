<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../app/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../app/controllers.php';
$app->run();
