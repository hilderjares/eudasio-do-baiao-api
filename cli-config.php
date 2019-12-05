<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

$container = require_once __DIR__ . '/public/bootstrap.php';

return ConsoleRunner::createHelperSet($container['em']);
