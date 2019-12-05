<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

$container = include './bootstrap.php';

$app = new App($container);

$app->get('/', 'App\Controller\IndexController');

$app->get('/items', 'App\Controller\ItemController:index');
$app->post('/item', 'App\Controller\ItemController:create');
$app->put('/item/{id}', 'App\Controller\ItemController:update');
$app->delete('/item/{id}', 'App\Controller\ItemController:delete');

$app->get('/clients', 'App\Controller\ClientController:index');
$app->post('/client', 'App\Controller\ClientController:create');
$app->get('/client/{id}/requests', 'App\Controller\ClientController:show');
$app->get('/client/request/{id}/buy', 'App\Controller\ClientController:buy');
$app->delete('/client/request/{id}', 'App\Controller\RequestController:delete');

$app->get('/requests', 'App\Controller\RequestController:index');
$app->post('/request', 'App\Controller\RequestController:create');

$app->run();
