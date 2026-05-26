SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS article_category;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE articles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    image VARCHAR(500) NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NULL,
    body LONGTEXT NOT NULL,
    views INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_articles_slug (slug),
    KEY idx_articles_published_at (published_at),
    KEY idx_articles_views (views)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE article_category (
    article_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    KEY idx_article_category_category_id (category_id),
    CONSTRAINT fk_article_category_article
        FOREIGN KEY (article_id) REFERENCES articles (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_article_category_category
        FOREIGN KEY (category_id) REFERENCES categories (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
