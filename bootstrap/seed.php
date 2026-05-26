<?php

declare(strict_types=1);

use App\Core\Database;
use App\Services\DatabaseSeeder;
use Dotenv\Dotenv;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

if (is_readable($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}

$database = new Database(require $root . '/config/database.php');

(new DatabaseSeeder($database))->run();

echo "Database seeded successfully.\n";
