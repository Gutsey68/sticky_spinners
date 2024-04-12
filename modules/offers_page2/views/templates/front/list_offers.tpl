{extends file='page.tpl'}
{block name='page_content'}
    <div class="offers_page">
        <h1>Annonces de vinyles</h1>
        {if $offers|@count > 0}
            <ul>
                {foreach from=$offers item=offer}
                    <li>
                        <h2>{$offer.title}</h2>
                        <p>{$offer.description}</p>
                        <img src="{$offer.image_url}" alt="{$offer.title}"/>
                        <p>Posté par: {$offer.customer_firstname}</p>
                    </li>
                {/foreach}
            </ul>
        {else}
            <p>Pas encore d'annonces</p>
        {/if}
        {if $customer.is_logged}
            <a href="{$link->getModuleLink('offers_page2', 'PostOffers')}" >
                Poster une annonce
            </a>
        {/if}
    </div>
{/block}
