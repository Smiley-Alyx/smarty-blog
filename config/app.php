<?php

declare(strict_types=1);

return [
    'name' => 'Smarty Blog',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
    'templates_path' => dirname(__DIR__) . '/templates',
    'smarty_compile_dir' => dirname(__DIR__) . '/' . ltrim((string) ($_ENV['SMARTY_COMPILE_DIR'] ?? 'var/smarty/compile'), '/'),
    'smarty_cache_dir' => dirname(__DIR__) . '/' . ltrim((string) ($_ENV['SMARTY_CACHE_DIR'] ?? 'var/smarty/cache'), '/'),
];
