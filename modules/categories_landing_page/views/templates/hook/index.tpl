{if isset($categories) && $categories|count > 0}
    <h2 id="home-categories">GENRES MUSICAUX</h2>
    <div class="home-categories-display">
        {foreach from=$categories item=category}
            <div class="category-block">
                <a href="{$category.link}" title="{$category.name}">
                    {if isset($category.image) && $category.image}
                        <div class="category-image">
                            <img src="{$category.image}" alt="{$category.name}" />
                        </div>
                    {/if}
                    <h3 class="category-name">{$category.name}</h3>
                </a>
            </div>
        {/foreach}
    </div>
{/if}