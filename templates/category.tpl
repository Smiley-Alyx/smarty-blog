<nav class="breadcrumbs">
    <a href="/">Главная</a>
    <span>/</span>
    <span>{$category.title|escape}</span>
</nav>

<h1>{$category.title|escape}</h1>

{if $category.description}
    <p class="category-description">{$category.description|escape}</p>
{/if}

<div class="sort-controls">
    <span>Сортировка:</span>
    <a href="/category/{$category.slug|escape}?sort=date&amp;page=1" class="{if $sort == 'date'}is-active{/if}">По дате</a>
    <a href="/category/{$category.slug|escape}?sort=views&amp;page=1" class="{if $sort == 'views'}is-active{/if}">По просмотрам</a>
</div>

{if $articles|@count > 0}
    <ul class="article-list">
        {foreach $articles as $article}
            <li class="article-card">
                {if $article.image}
                    <img src="{$article.image|escape}" alt="{$article.title|escape}" class="article-card__image">
                {/if}
                <div class="article-card__body">
                    <h2>
                        <a href="/article/{$article.slug|escape}">{$article.title|escape}</a>
                    </h2>
                    {if $article.description}
                        <p>{$article.description|escape}</p>
                    {/if}
                    <p class="article-meta">
                        {if $article.published_at}
                            <time datetime="{$article.published_at|escape}">{$article.published_at|escape}</time>
                        {/if}
                        <span>Просмотров: {$article.views|escape}</span>
                    </p>
                </div>
            </li>
        {/foreach}
    </ul>
{else}
    <p class="empty-state">В этой категории пока нет статей.</p>
{/if}

{if $pagination.total_pages > 1}
    <nav class="pagination" aria-label="Пагинация">
        {if $pagination.has_prev}
            <a href="/category/{$category.slug|escape}?sort={$sort|escape}&amp;page={$pagination.prev_page|escape}">Назад</a>
        {/if}

        <span>Страница {$pagination.current_page|escape} из {$pagination.total_pages|escape}</span>

        {if $pagination.has_next}
            <a href="/category/{$category.slug|escape}?sort={$sort|escape}&amp;page={$pagination.next_page|escape}">Вперёд</a>
        {/if}
    </nav>
{/if}
