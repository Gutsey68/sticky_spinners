{extends file='page.tpl'}
{block name='page_content'}
    <div class="offers_page_all_offers">
        <h1>Toutes les annonces</h1>
        {if $offers|@count > 0}
            <ul class="offers-list">
                {foreach from=$offers item=offer}
                    <li class="offer-item">
                        <h2>{$offer.title|escape:'html'}</h2>
                        <p>{$offer.description|escape:'html'}</p>
                        {if $offer.image}
                            <img src="{$offer.image|escape:'html'}" alt="Image de l'annonce" style="max-width: 100px; max-height: 100px;">
                        {/if}
                    </li>
                {/foreach}
            </ul>
        {else}
            <p>Aucune annonce pour le moment.</p>
        {/if}
    </div>
{/block}
