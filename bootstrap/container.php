<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Core\Container;
use App\Core\Database;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\DatabaseSeeder;
use Dotenv\Dotenv;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

if (is_readable($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}

$config = require $root . '/config/app.php';

foreach ([$config['smarty_compile_dir'], $config['smarty_cache_dir']] as $directory) {
    if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException(sprintf('Unable to create directory: %s', $directory));
    }
}

$container = new Container();
$container->set('config', $config);
$container->set('root', $root);

$container->singleton(Database::class, static function (Container $container): Database {
    return new Database(require $container->get('root') . '/config/database.php');
});

$container->singleton(View::class, static function (Container $container): View {
    $config = $container->get('config');

    return new View(
        $config['templates_path'],
        $config['smarty_compile_dir'],
        $config['smarty_cache_dir'],
        $config['debug'],
    );
});

$container->singleton(CategoryRepository::class, static function (Container $container): CategoryRepository {
    return new CategoryRepository($container->get(Database::class));
});

$container->singleton(ArticleRepository::class, static function (Container $container): ArticleRepository {
    return new ArticleRepository($container->get(Database::class));
});

$container->singleton(HomeController::class, static function (Container $container): HomeController {
    return new HomeController(
        $container->get(View::class),
        $container->get(CategoryRepository::class),
    );
});

$container->singleton(CategoryController::class, static function (Container $container): CategoryController {
    return new CategoryController(
        $container->get(View::class),
        $container->get(CategoryRepository::class),
        $container->get('config')['articles_per_page'],
    );
});

$container->singleton(ArticleController::class, static function (Container $container): ArticleController {
    return new ArticleController(
        $container->get(View::class),
        $container->get(ArticleRepository::class),
        $container->get(CategoryRepository::class),
    );
});

$container->singleton(DatabaseSeeder::class, static function (Container $container): DatabaseSeeder {
    return new DatabaseSeeder($container->get(Database::class));
});

return $container;
