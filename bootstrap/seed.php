<?php

declare(strict_types=1);

use App\Services\DatabaseSeeder;

/** @var \App\Core\Container $container */
$container = require __DIR__ . '/container.php';

$container->get(DatabaseSeeder::class)->run();

echo "Database seeded successfully.\n";
