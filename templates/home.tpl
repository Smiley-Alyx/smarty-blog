<h1>Главная</h1>

{foreach $sections as $section}
<section class="category-section">
    <h2>{$section.category.title|escape}</h2>

    {if $section.category.description}
        <p class="category-description">{$section.category.description|escape}</p>
    {/if}

    {if $section.articles|@count > 0}
        <ul class="article-list">
            {foreach $section.articles as $article}
                <li class="article-card">
                    {if $article.image}
                        <img src="{$article.image|escape}" alt="{$article.title|escape}" class="article-card__image">
                    {/if}
                    <div class="article-card__body">
                        <h3>
                            <a href="/article/{$article.slug|escape}">{$article.title|escape}</a>
                        </h3>
                        {if $article.description}
                            <p>{$article.description|escape}</p>
                        {/if}
                        {if $article.published_at}
                            <time datetime="{$article.published_at|escape}">{$article.published_at|escape}</time>
                        {/if}
                    </div>
                </li>
            {/foreach}
        </ul>
    {/if}

    <p>
        <a href="/category/{$section.category.slug|escape}" class="btn">Все статьи</a>
    </p>
</section>
{foreachelse}
<p class="empty-state">Пока нет опубликованных статей.</p>
{/foreach}
