<?php

declare(strict_types=1);

namespace App\Core;

final class Application
{
    public function __construct(
        private Router $router,
        private Request $request,
    ) {
    }

    public function handle(): Response
    {
        return $this->router->dispatch($this->request);
    }
}
