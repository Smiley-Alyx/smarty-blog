<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable(Request): Response> */
    private array $routes = [];

    /** @var list<array{method: string, pattern: string, handler: callable(Request): Response}> */
    private array $dynamicRoutes = [];

    public function get(string $path, callable $handler): void
    {
        if (str_contains($path, '{')) {
            $this->dynamicRoutes[] = [
                'method' => 'GET',
                'pattern' => $path,
                'handler' => $handler,
            ];

            return;
        }

        $this->routes[$this->routeKey('GET', $path)] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$this->routeKey($request->method, $request->path())] ?? null;

        if ($handler !== null) {
            return $handler($request);
        }

        $match = $this->matchDynamicRoute($request->method, $request->path());

        if ($match === null) {
            return new Response('Not Found', 404);
        }

        return $match['handler']($request->withParams($match['params']));
    }

    /**
     * @return array{handler: callable(Request): Response, params: array<string, string>}|null
     */
    private function matchDynamicRoute(string $method, string $path): ?array
    {
        $method = strtoupper($method);

        foreach ($this->dynamicRoutes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $regex = $this->patternToRegex($route['pattern']);

            if (!preg_match($regex, $path, $matches)) {
                continue;
            }

            preg_match_all('/\{(\w+)\}/', $route['pattern'], $paramNames);
            $params = [];

            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index + 1];
            }

            return [
                'handler' => $route['handler'],
                'params' => $params,
            ];
        }

        return null;
    }

    private function patternToRegex(string $pattern): string
    {
        $regex = preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern);

        return '#^' . $regex . '$#';
    }

    private function routeKey(string $method, string $path): string
    {
        return strtoupper($method) . ' ' . $path;
    }
}
