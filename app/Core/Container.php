<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Container
{
    /** @var array<string, callable(self): mixed> */
    private array $factories = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function set(string $id, mixed $value): void
    {
        $this->instances[$id] = $value;
    }

    public function singleton(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (!isset($this->factories[$id])) {
            throw new RuntimeException(sprintf('Service "%s" is not registered.', $id));
        }

        $this->instances[$id] = $this->factories[$id]($this);

        return $this->instances[$id];
    }
}
