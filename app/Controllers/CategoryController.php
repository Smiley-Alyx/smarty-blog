<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;

class CategoryController
{
    public function __construct(
        private View $view,
        private CategoryRepository $categoryRepository,
        private int $articlesPerPage,
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->param('slug', '');
        $category = $this->categoryRepository->getCategoryBySlug($slug);

        if ($category === null) {
            return new Response('Not Found', 404);
        }

        $sort = $this->validateSort($request->query('sort', 'date'));
        $page = $this->validatePage($request->query('page', 1));
        $perPage = max(1, $this->articlesPerPage);

        $total = $this->categoryRepository->countArticlesByCategory($category->id);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;
        $articles = $this->categoryRepository->getArticlesByCategory(
            $category->id,
            $sort,
            $perPage,
            $offset,
        );

        $articleRows = [];

        foreach ($articles as $article) {
            $articleRows[] = [
                'title' => $article->title,
                'slug' => $article->slug,
                'description' => $article->description,
                'image' => $article->image,
                'views' => $article->views,
                'published_at' => $this->formatDate($article->publishedAt),
            ];
        }

        $html = $this->view->render('category.tpl', [
            'title' => $category->title . ' — Smarty Blog',
            'category' => [
                'title' => $category->title,
                'slug' => $category->slug,
                'description' => $category->description,
            ],
            'articles' => $articleRows,
            'sort' => $sort,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => max(1, $page - 1),
                'next_page' => min($totalPages, $page + 1),
            ],
        ]);

        return new Response($html);
    }

    private function validateSort(mixed $sort): string
    {
        if (!is_string($sort)) {
            return 'date';
        }

        return in_array($sort, ['date', 'views'], true) ? $sort : 'date';
    }

    private function validatePage(mixed $page): int
    {
        $page = is_numeric($page) ? (int) $page : 1;

        return $page < 1 ? 1 : $page;
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
