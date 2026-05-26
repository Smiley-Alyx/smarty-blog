<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:'Smarty Blog'|escape}</title>
</head>
<body>
    <header>
        <a href="/">Smarty Blog</a>
    </header>

    <main>
        {$content nofilter}
    </main>

    <footer>
        <small>&copy; {$year|escape} Smarty Blog</small>
    </footer>
</body>
</html>
