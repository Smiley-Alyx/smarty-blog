<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Core\Application;
use App\Core\Request;
use App\Core\Router;

/** @var \App\Core\Container $container */
$container = require __DIR__ . '/container.php';

$router = new Router();

$router->get('/', [$container->get(HomeController::class), 'index']);
$router->get('/category/{slug}', [$container->get(CategoryController::class), 'show']);
$router->get('/article/{slug}', [$container->get(ArticleController::class), 'show']);

return new Application($router, Request::fromGlobals());
