<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Core\Application;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use Dotenv\Dotenv;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

if (is_readable($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}

$config = require $root . '/config/app.php';
$databaseConfig = require $root . '/config/database.php';

foreach ([$config['smarty_compile_dir'], $config['smarty_cache_dir']] as $directory) {
    if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException(sprintf('Unable to create directory: %s', $directory));
    }
}

$database = new Database($databaseConfig);

$categoryRepository = new CategoryRepository($database);
$articleRepository = new ArticleRepository($database);

$request = Request::fromGlobals();
$view = new View(
    $config['templates_path'],
    $config['smarty_compile_dir'],
    $config['smarty_cache_dir'],
    $config['debug'],
);
$router = new Router();

$homeController = new HomeController($view, $categoryRepository);
$categoryController = new CategoryController(
    $view,
    $categoryRepository,
    $config['articles_per_page'],
);
$articleController = new ArticleController($view, $articleRepository, $categoryRepository);

$router->get('/', [$homeController, 'index']);
$router->get('/category/{slug}', [$categoryController, 'show']);
$router->get('/article/{slug}', [$articleController, 'show']);

return new Application($router, $request);
