<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

final class HomeController
{
    public function __construct(
        private View $view,
    ) {
    }

    public function index(Request $request): Response
    {
        $html = $this->view->render('home.tpl', [
            'title' => 'Smarty Blog',
            'phpVersion' => PHP_VERSION,
        ]);

        return new Response($html);
    }
}
