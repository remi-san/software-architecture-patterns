<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$app = require __DIR__.'/../app/app.php';
require __DIR__.'/dev.php';

return ConsoleRunner::createHelperSet($app['orm.em']);