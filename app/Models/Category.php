<?php

declare(strict_types=1);

namespace App\Models;

class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $description,
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
            (string) $row['title'],
            (string) $row['slug'],
            isset($row['description']) ? (string) $row['description'] : null,
            (string) $row['created_at'],
            (string) $row['updated_at'],
        );
    }
}
