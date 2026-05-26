<?php

declare(strict_types=1);

namespace App\Models;

class Article
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $image,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly string $body,
        public readonly int $views,
        public readonly ?string $publishedAt,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            isset($row['image']) ? (string) $row['image'] : null,
            (string) $row['title'],
            (string) $row['slug'],
            isset($row['description']) ? (string) $row['description'] : null,
            (string) $row['body'],
            (int) $row['views'],
            isset($row['published_at']) ? (string) $row['published_at'] : null,
            (string) $row['created_at'],
            (string) $row['updated_at'],
        );
    }
}
