<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\ConnectionRepository;

final class HomeController
{
    public function __construct(
        private View $view,
        private ConnectionRepository $connectionRepository,
        private bool $debug,
    ) {
    }

    public function index(Request $request): Response
    {
        $data = [
            'title' => 'Smarty Blog',
            'phpVersion' => PHP_VERSION,
            'dbConnected' => null,
        ];

        if ($this->debug) {
            $data['dbConnected'] = $this->connectionRepository->isAlive();
        }

        $html = $this->view->render('home.tpl', $data);

        return new Response($html);
    }
}
