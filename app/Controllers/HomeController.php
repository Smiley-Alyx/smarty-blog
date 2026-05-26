<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;

class HomeController
{
    public function __construct(
        private View $view,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function index(Request $request): Response
    {
        $sections = [];

        foreach ($this->categoryRepository->getCategoriesWithArticles() as $category) {
            $articles = [];

            foreach ($this->categoryRepository->getLatestByCategory($category->id, 3) as $article) {
                $articles[] = [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'description' => $article->description,
                    'image' => $article->image,
                    'published_at' => $this->formatDate($article->publishedAt),
                ];
            }

            $sections[] = [
                'category' => [
                    'title' => $category->title,
                    'slug' => $category->slug,
                    'description' => $category->description,
                ],
                'articles' => $articles,
            ];
        }

        $html = $this->view->render('home.tpl', [
            'title' => 'Главная — Smarty Blog',
            'sections' => $sections,
        ]);

        return new Response($html);
    }

    private function formatDate(?string $dateTime): string
    {
        if ($dateTime === null) {
            return '';
        }

        $timestamp = strtotime($dateTime);

        return $timestamp === false ? '' : date('d.m.Y', $timestamp);
    }
}
