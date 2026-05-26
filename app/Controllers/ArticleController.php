<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

class ArticleController
{
    public function __construct(
        private View $view,
        private ArticleRepository $articleRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->param('slug', '');
        $article = $this->articleRepository->getArticleBySlug($slug);

        if ($article === null) {
            return new Response('Not Found', 404);
        }

        $this->articleRepository->incrementViews($article->id);

        $categories = [];

        foreach ($this->categoryRepository->getCategoriesForArticle($article->id) as $category) {
            $categories[] = [
                'title' => $category->title,
                'slug' => $category->slug,
            ];
        }

        $relatedArticles = [];

        foreach ($this->articleRepository->getRelatedArticles($article->id, 3) as $related) {
            $relatedArticles[] = [
                'title' => $related->title,
                'slug' => $related->slug,
                'description' => $related->description,
                'image' => $related->image,
                'published_at' => $this->formatDate($related->publishedAt),
            ];
        }

        $html = $this->view->render('article.tpl', [
            'title' => $article->title . ' — Smarty Blog',
            'article' => [
                'title' => $article->title,
                'slug' => $article->slug,
                'description' => $article->description,
                'body' => nl2br(htmlspecialchars($article->body, ENT_QUOTES, 'UTF-8')),
                'image' => $article->image,
                'views' => $article->views + 1,
                'published_at' => $this->formatDate($article->publishedAt),
            ],
            'categories' => $categories,
            'related_articles' => $relatedArticles,
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
