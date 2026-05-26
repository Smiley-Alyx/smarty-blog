<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

class DatabaseSeeder
{
    public function __construct(
        private Database $database,
    ) {
    }

    public function run(): void
    {
        $pdo = $this->database->connection();

        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $pdo->exec('TRUNCATE TABLE article_category');
        $pdo->exec('TRUNCATE TABLE articles');
        $pdo->exec('TRUNCATE TABLE categories');
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $categories = [
            ['tech', 'Технологии', 'Разработка, инструменты и всё про код.'],
            ['travel', 'Путешествия', 'Маршруты, впечатления и советы из дороги.'],
            ['food', 'Кулинария', 'Рецепты, продукты и кухонные эксперименты.'],
            ['life', 'Личное', 'Заметки о жизни, привычках и балансе.'],
        ];

        $categoryStmt = $pdo->prepare(
            'INSERT INTO categories (slug, title, description) VALUES (:slug, :title, :description)',
        );

        foreach ($categories as [$slug, $title, $description]) {
            $categoryStmt->execute([
                'slug' => $slug,
                'title' => $title,
                'description' => $description,
            ]);
        }

        /** @var array<string, int> $categoryIds */
        $categoryIds = $pdo->query('SELECT slug, id FROM categories')->fetchAll(PDO::FETCH_KEY_PAIR);

        $articles = $this->articlesData();

        $articleStmt = $pdo->prepare(
            <<<SQL
            INSERT INTO articles (image, title, slug, description, body, views, published_at)
            VALUES (:image, :title, :slug, :description, :body, :views, :published_at)
            SQL,
        );

        $linkStmt = $pdo->prepare(
            'INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)',
        );

        foreach ($articles as $article) {
            $articleStmt->execute([
                'image' => $article['image'],
                'title' => $article['title'],
                'slug' => $article['slug'],
                'description' => $article['description'],
                'body' => $article['body'],
                'views' => $article['views'],
                'published_at' => $article['published_at'],
            ]);

            $articleId = (int) $pdo->lastInsertId();

            foreach ($article['categories'] as $categorySlug) {
                $linkStmt->execute([
                    'article_id' => $articleId,
                    'category_id' => $categoryIds[$categorySlug],
                ]);
            }
        }
    }

    /**
     * @return list<array{
     *     image: string,
     *     title: string,
     *     slug: string,
     *     description: string,
     *     body: string,
     *     views: int,
     *     published_at: string,
     *     categories: list<string>
     * }>
     */
    private function articlesData(): array
    {
        return [
            [
                'image' => '/assets/images/placeholder-php.svg',
                'title' => 'PHP 8.2: что полезного в ежедневной работе',
                'slug' => 'php-82-daily-features',
                'description' => 'Короткий обзор возможностей PHP 8.2 для бэкенд-разработки.',
                'body' => "В PHP 8.2 появились readonly-классы, расширена работа с типами и улучшены сообщения об ошибках.\n\nДля небольшого проекта без фреймворка это отличный баланс между простотой и строгостью.",
                'views' => 128,
                'published_at' => '2026-05-10 09:00:00',
                'categories' => ['tech'],
            ],
            [
                'image' => '/assets/images/placeholder-smarty.svg',
                'title' => 'Smarty без фреймворка: шаблоны и layout',
                'slug' => 'smarty-templates-without-framework',
                'description' => 'Как отделить логику отображения от контроллеров.',
                'body' => "Smarty помогает держать HTML в шаблонах, а PHP - в контроллерах и репозиториях.\n\nLayout + content - простой способ собрать страницу без лишней магии.",
                'views' => 94,
                'published_at' => '2026-05-12 11:30:00',
                'categories' => ['tech'],
            ],
            [
                'image' => '/assets/images/placeholder-docker.svg',
                'title' => 'Docker для локального блога: nginx, php-fpm, mysql',
                'slug' => 'docker-local-blog-stack',
                'description' => 'Минимальный docker-compose для учебного проекта.',
                'body' => "Три контейнера - nginx, PHP-FPM и MySQL - закрывают базовые потребности.\n\nГлавное - единая точка входа в public/ и volume с кодом проекта.",
                'views' => 210,
                'published_at' => '2026-05-15 14:00:00',
                'categories' => ['tech', 'life'],
            ],
            [
                'image' => '/assets/images/placeholder-mysql.svg',
                'title' => 'PDO и prepared statements в репозиториях',
                'slug' => 'pdo-prepared-statements-repositories',
                'description' => 'Почему SQL живёт только в Repository layer.',
                'body' => "Репозиторий инкапсулирует запросы к БД, а контроллер работает с моделями.\n\nPrepared statements защищают от SQL-инъекций и делают код предсказуемым.",
                'views' => 76,
                'published_at' => '2026-05-18 10:15:00',
                'categories' => ['tech'],
            ],
            [
                'image' => '/assets/images/placeholder-router.svg',
                'title' => 'Самописный роутер для небольшого приложения',
                'slug' => 'custom-router-small-app',
                'description' => 'Статические и динамические маршруты без лишней сложности.',
                'body' => "Для тестового задания достаточно GET-маршрутов и параметров вроде {slug}.\n\nРоутер возвращает 404, если обработчик не найден.",
                'views' => 55,
                'published_at' => '2026-05-20 16:45:00',
                'categories' => ['tech'],
            ],
            [
                'image' => '/assets/images/placeholder-berlin.svg',
                'title' => 'Выходные в Берлине: музеи и кофейни',
                'slug' => 'weekend-in-berlin',
                'description' => 'Маршрут на два дня без спешки.',
                'body' => "Утро началось с кофе в Kreuzberg, днём - музей, вечером - прогулка у Spree.\n\nГород удобен пешком, если заранее выбрать 2–3 точки.",
                'views' => 142,
                'published_at' => '2026-05-08 08:00:00',
                'categories' => ['travel', 'life'],
            ],
            [
                'image' => '/assets/images/placeholder-baltic.svg',
                'title' => 'Поездка на Балтику: что взять с собой',
                'slug' => 'baltic-sea-packing-list',
                'description' => 'Чек-лист для короткой поездки к морю.',
                'body' => "Ветровка, удобная обувь и power bank - must have.\n\nДаже в мае погода меняется несколько раз за день.",
                'views' => 88,
                'published_at' => '2026-05-14 12:00:00',
                'categories' => ['travel'],
            ],
            [
                'image' => '/assets/images/placeholder-train.svg',
                'title' => 'Ночной поезд: как выспаться в плацкарте',
                'slug' => 'sleeping-on-night-train',
                'description' => 'Пара практичных советов из личного опыта.',
                'body' => "Маска для сна, беруши и тёплый слой одежды решают половину проблем.\n\nЛучше выбрать нижнюю полку и место подальше от тамбура.",
                'views' => 61,
                'published_at' => '2026-05-22 19:30:00',
                'categories' => ['travel', 'life'],
            ],
            [
                'image' => '/assets/images/placeholder-pasta.svg',
                'title' => 'Паста aglio e olio за 20 минут',
                'slug' => 'pasta-aglio-e-olio',
                'description' => 'Простой ужин из чеснока, оливкового масла и петрушки.',
                'body' => "Обжарьте чеснок на оливковом масле до золотистого цвета, добавьте пасту и немного воды от варки.\n\nВ конце - петрушка и перец.",
                'views' => 173,
                'published_at' => '2026-05-11 18:00:00',
                'categories' => ['food'],
            ],
            [
                'image' => '/assets/images/placeholder-soup.svg',
                'title' => 'Суп-пюре из тыквы: базовый рецепт',
                'slug' => 'pumpkin-soup-recipe',
                'description' => 'Мягкий суп на обед или лёгкий ужин.',
                'body' => "Запеките тыкву, измельчите блендером с бульоном и сливками.\n\nПодавайте с тостами и кунжутом.",
                'views' => 99,
                'published_at' => '2026-05-17 13:20:00',
                'categories' => ['food', 'life'],
            ],
            [
                'image' => '/assets/images/placeholder-bread.svg',
                'title' => 'Домашний хлеб без замеса: no-knead',
                'slug' => 'no-knead-bread',
                'description' => 'Хрустящая корочка и воздушный мякиш.',
                'body' => "Смешайте ингредиенты, оставьте на ночь, утром выпеките в чугуне.\n\nГлавное - дать тесту достаточно времени.",
                'views' => 134,
                'published_at' => '2026-05-21 07:45:00',
                'categories' => ['food'],
            ],
            [
                'image' => '/assets/images/placeholder-habits.svg',
                'title' => 'Утренние привычки разработчика',
                'slug' => 'developer-morning-habits',
                'description' => 'Как начать день без хаоса в задачах.',
                'body' => "15 минут на план, один главный приоритет и короткая прогулка до ноутбука.\n\nНе открывайте мессенджеры до первого завершённого шага.",
                'views' => 67,
                'published_at' => '2026-05-19 06:30:00',
                'categories' => ['life'],
            ],
            [
                'image' => '/assets/images/placeholder-books.svg',
                'title' => 'Что читать про архитектуру приложений',
                'slug' => 'books-about-app-architecture',
                'description' => 'Небольшая подборка для junior и middle.',
                'body' => "Начните с основ HTTP и баз данных, затем переходите к паттернам и рефакторингу.\n\nГлавное - сразу пробовать идеи на маленьких проектах.",
                'views' => 48,
                'published_at' => '2026-05-23 15:00:00',
                'categories' => ['life', 'tech'],
            ],
            [
                'image' => '/assets/images/placeholder-walk.svg',
                'title' => 'Почему прогулка помогает при отладке',
                'slug' => 'walking-helps-debugging',
                'description' => 'Смена контекста иногда важнее ещё одного var_dump.',
                'body' => "Когда застряли на баге, 20 минут на улице часто дают свежий взгляд.\n\nВозвращайтесь к коду с одной конкретной гипотезой.",
                'views' => 39,
                'published_at' => '2026-05-24 20:10:00',
                'categories' => ['life'],
            ],
        ];
    }
}
