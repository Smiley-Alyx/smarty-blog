<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;

final class ArticleRepository extends AbstractRepository
{
    public function getArticleBySlug(string $slug): ?Article
    {
        $row = $this->fetchOne(
            <<<SQL
            SELECT *
            FROM articles
            WHERE slug = :slug
              AND published_at IS NOT NULL
            LIMIT 1
            SQL,
            ['slug' => $slug],
        );

        return $row === null ? null : Article::fromRow($row);
    }

    public function incrementViews(int $articleId): void
    {
        $this->execute(
            'UPDATE articles SET views = views + 1 WHERE id = :id',
            ['id' => $articleId],
        );
    }

    /**
     * @return list<Article>
     */
    public function getRelatedArticles(int $articleId, int $limit = 3): array
    {
        $limit = max(1, $limit);

        $rows = $this->fetchAll(
            <<<SQL
            SELECT a.*, COUNT(DISTINCT ac.category_id) AS shared_categories
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE a.published_at IS NOT NULL
              AND a.id != :article_id
              AND ac.category_id IN (
                  SELECT category_id
                  FROM article_category
                  WHERE article_id = :filter_article_id
              )
            GROUP BY a.id, a.image, a.title, a.slug, a.description, a.body,
                     a.views, a.published_at, a.created_at, a.updated_at
            ORDER BY shared_categories DESC, a.published_at DESC
            LIMIT {$limit}
            SQL,
            [
                'article_id' => $articleId,
                'filter_article_id' => $articleId,
            ],
        );

        return array_map(static fn (array $row): Article => Article::fromRow($row), $rows);
    }
}
