<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use App\Models\Category;

final class CategoryRepository extends AbstractRepository
{
    /**
     * @return list<Category>
     */
    public function getCategoriesWithArticles(): array
    {
        $rows = $this->fetchAll(
            <<<SQL
            SELECT DISTINCT c.*
            FROM categories c
            INNER JOIN article_category ac ON ac.category_id = c.id
            INNER JOIN articles a ON a.id = ac.article_id AND a.published_at IS NOT NULL
            ORDER BY c.title ASC
            SQL,
        );

        return array_map(static fn (array $row): Category => Category::fromRow($row), $rows);
    }

    /**
     * @return list<Article>
     */
    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $limit = max(1, $limit);

        $rows = $this->fetchAll(
            <<<SQL
            SELECT a.*
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
              AND a.published_at IS NOT NULL
            ORDER BY a.published_at DESC
            LIMIT {$limit}
            SQL,
            ['category_id' => $categoryId],
        );

        return array_map(static fn (array $row): Article => Article::fromRow($row), $rows);
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        $row = $this->fetchOne(
            'SELECT * FROM categories WHERE slug = :slug LIMIT 1',
            ['slug' => $slug],
        );

        return $row === null ? null : Category::fromRow($row);
    }

    /**
     * @return list<Article>
     */
    public function getArticlesByCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = $sort === 'views'
            ? 'a.views DESC, a.published_at DESC'
            : 'a.published_at DESC';
        $limit = max(1, $limit);
        $offset = max(0, $offset);

        $sql = <<<SQL
            SELECT a.*
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
              AND a.published_at IS NOT NULL
            ORDER BY {$orderBy}
            LIMIT {$limit} OFFSET {$offset}
            SQL;

        $rows = $this->fetchAll($sql, ['category_id' => $categoryId]);

        return array_map(static fn (array $row): Article => Article::fromRow($row), $rows);
    }

    public function countArticlesByCategory(int $categoryId): int
    {
        $row = $this->fetchOne(
            <<<SQL
            SELECT COUNT(DISTINCT a.id) AS total
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
              AND a.published_at IS NOT NULL
            SQL,
            ['category_id' => $categoryId],
        );

        return (int) ($row['total'] ?? 0);
    }

    /**
     * @return list<Category>
     */
    public function getCategoriesForArticle(int $articleId): array
    {
        $rows = $this->fetchAll(
            <<<SQL
            SELECT c.*
            FROM categories c
            INNER JOIN article_category ac ON ac.category_id = c.id
            WHERE ac.article_id = :article_id
            ORDER BY c.title ASC
            SQL,
            ['article_id' => $articleId],
        );

        return array_map(static fn (array $row): Category => Category::fromRow($row), $rows);
    }
}
