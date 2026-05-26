# Smarty Blog

Простой демонстрационный блог на чистом PHP 8.2, MySQL и Smarty без фреймворков.

## Стек

- PHP 8.2
- MySQL 8
- Smarty 5
- Composer
- Docker + Docker Compose
- SCSS (сборка через dart-sass без npm)

## Функциональность

- Главная страница:
  - вывод категорий, в которых есть статьи;
  - по 3 последние статьи в каждой категории;
  - кнопка «Все статьи».
- Страница категории:
  - название и описание категории;
  - список статей;
  - сортировка по дате и просмотрам;
  - пагинация.
- Страница статьи:
  - изображение, заголовок, описание, текст, просмотры;
  - увеличение просмотров при открытии статьи;
  - категории статьи;
  - блок из 3 похожих статей;
  - похожие статьи выбираются по пересечению категорий.
- CLI:
  - сидинг базы (`php cli seed`);
  - сборка SCSS в CSS (`php cli build-css`).

## Архитектура и структура

```text
app/
  Controllers/    HTTP-контроллеры
  Core/           Application, Router, Request, Response, View, Database, Container
  Models/         Модели Category / Article
  Repositories/   Все SQL-запросы (только здесь)
  Services/       Сервисы (например, DatabaseSeeder)
assets/
  scss/           Исходники SCSS
bootstrap/        Инициализация приложения и CLI (container, app, seed)
config/           Конфигурация приложения и БД
database/         SQL-схема
docker/           Конфиги PHP и nginx
public/           Точка входа и публичные ассеты
templates/        Smarty-шаблоны
```

## Технические решения

- Самописный Router с поддержкой динамических роутов (`/category/{slug}`, `/article/{slug}`).
- Минимальный ручной DI через контейнер (`App\Core\Container`) для централизованного подключения.
- Доступ к БД через PDO с безопасными настройками:
  - `ERRMODE_EXCEPTION`
  - `ATTR_EMULATE_PREPARES = false`
- Repository layer для изоляции SQL от контроллеров и шаблонов.
- В шаблонах только отображение.
- SCSS хранится в `assets/scss/style.scss`, CSS собирается в `public/assets/css/style.css`.

## Запуск проекта через Docker

### 1) Подготовка env

```bash
cp .env.example .env
```

### 2) Сборка и запуск контейнеров

```bash
docker compose up -d --build
```

Приложение будет доступно по адресу: [http://localhost:8080](http://localhost:8080)

### 3) Создание схемы БД

```bash
docker compose exec -T mysql mysql -u blog -psecret blog < database/schema.sql
```

### 4) Сидинг тестовыми данными

```bash
docker compose exec php php cli seed
```

## Локальные CLI-команды

```bash
php cli seed
php cli build-css
```

> Обычно удобнее выполнять через Docker:
>
> - `docker compose exec php php cli seed`
> - `docker compose exec php php cli build-css`

## SCSS

- Исходник: `assets/scss/style.scss`
- Результат: `public/assets/css/style.css`
- Сборка:
  - вручную: `php cli build-css`
  - автоматически: при старте php-контейнера (эндпоинт вызывает скрипт сборки)

## Что можно улучшить

- Добавить миграции и откат схемы вместо единого `schema.sql`.
- Добавить тесты (unit для репозиториев и smoke/e2e для HTTP-страниц).
- Вынести повторяющееся форматирование дат в отдельный helper.
- Добавить обработку ошибок/страницы 500 с аккуратным UI.
- Добавить CI-пайплайн (lint + проверки запуска с нуля).

## Использование ИИ

ИИ использовался как вспомогательный инструмент для ускорения рутинных задач:

- генерации шаблонного кода;
- проверки структуры и отдельных SQL-запросов;
- заполнения сидерского контента (категории, статьи, описания, тексты для `DatabaseSeeder`);
- генерации SVG-плейсхолдеров для статей;
- проверки консистентности по этапам.

Архитектура, организация проекта, интеграция компонентов и финальная отладка выполнялись вручную.
