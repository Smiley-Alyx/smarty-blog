<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable(Request): Response> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$this->routeKey($method, $path)] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$this->routeKey($request->method, $request->path())] ?? null;

        if ($handler === null) {
            return new Response('Not Found', 404);
        }

        return $handler($request);
    }

    private function routeKey(string $method, string $path): string
    {
        return strtoupper($method) . ' ' . $path;
    }
}
