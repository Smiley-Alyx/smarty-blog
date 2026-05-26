<nav class="breadcrumbs">
    <a href="/">Главная</a>
    {if $categories|@count > 0}
        <span>/</span>
        <a href="/category/{$categories[0].slug|escape}">{$categories[0].title|escape}</a>
    {/if}
    <span>/</span>
    <span>{$article.title|escape}</span>
</nav>

<article class="article-detail">
    {if $article.image}
        <img src="{$article.image|escape}" alt="{$article.title|escape}" class="article-detail__image">
    {/if}

    <h1>{$article.title|escape}</h1>

    {if $article.description}
        <p class="article-detail__description">{$article.description|escape}</p>
    {/if}

    <p class="article-meta">
        {if $article.published_at}
            <time datetime="{$article.published_at|escape}">{$article.published_at|escape}</time>
        {/if}
        <span>Просмотров: {$article.views|escape}</span>
    </p>

    {if $categories|@count > 0}
        <ul class="article-categories">
            {foreach $categories as $category}
                <li>
                    <a href="/category/{$category.slug|escape}">{$category.title|escape}</a>
                </li>
            {/foreach}
        </ul>
    {/if}

    <div class="article-detail__body">
        {$article.body nofilter}
    </div>
</article>

{if $related_articles|@count > 0}
<section class="related-articles">
    <h2>Похожие статьи</h2>
    <ul class="article-list">
        {foreach $related_articles as $related}
            <li class="article-card">
                {if $related.image}
                    <img src="{$related.image|escape}" alt="{$related.title|escape}" class="article-card__image">
                {/if}
                <div class="article-card__body">
                    <h3>
                        <a href="/article/{$related.slug|escape}">{$related.title|escape}</a>
                    </h3>
                    {if $related.description}
                        <p>{$related.description|escape}</p>
                    {/if}
                    {if $related.published_at}
                        <time datetime="{$related.published_at|escape}">{$related.published_at|escape}</time>
                    {/if}
                </div>
            </li>
        {/foreach}
    </ul>
</section>
{/if}
