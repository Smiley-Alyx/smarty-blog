<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:'Smarty Blog'|escape}</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a href="/" class="site-brand">Smarty Blog</a>
        </div>
    </header>

    <main class="site-main">
        {$content nofilter}
    </main>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <small>&copy; {$year|escape} Smarty Blog</small>
        </div>
    </footer>
</body>
</html>
