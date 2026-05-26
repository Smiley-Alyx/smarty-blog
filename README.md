# Smarty Blog

Простой блог на чистом PHP 8.2+, MySQL и Smarty - тестовое задание без фреймворков и ORM.

## Стек

- PHP 8.2
- MySQL
- Smarty
- Composer
- Docker
- SCSS

## Структура проекта

```
app/
  Controllers/    # HTTP-контроллеры
  Core/           # Router, Request, View, Database
  Models/         # Сущности данных
  Repositories/   # Доступ к БД (SQL только здесь)
  Services/       # Бизнес-логика при необходимости
config/           # Конфигурация приложения
database/         # SQL-схема и миграции
public/           # Document root (index.php)
templates/        # Smarty-шаблоны
assets/           # Исходники SCSS, изображения
docker/           # Конфигурация Docker
```

## Требования

- PHP 8.2+
- Composer 2.x
- Docker и Docker Compose (для локальной разработки)

## Быстрый старт

> Подробные инструкции по запуску, схеме БД и сидерам будут добавлены на финальных этапах.

1. Скопируйте переменные окружения:

   ```bash
   cp .env.example .env
   ```

2. Установите зависимости:

   ```bash
   composer install
   ```

3. Запустите окружение через Docker (на этапе 2):

   ```bash
   docker compose up -d
   ```

## Лицензия

Тестовый проект
