{extends file='page.tpl'}
{block name='page_content'}
    <div class="offers_page container">
        <h1>Annonces de <span class="offer-accent">vinyles</span></h1>
        <p>
            Vous devez être connecter pour poster une annonce.
        </p>
        {if $customer.is_logged}
            <a href="{$link->getModuleLink('offers_page2', 'PostOffers')}" >
                Poster une annonce
            </a>
        {/if}
        {if $offers|@count > 0}
            <div class="offers-container">
                {foreach from=$offers item=offer}
                    <div class="offer-card">
                        <div class="offer-card-image">
                            <img src="{$offer.image_url}" alt="{$offer.title}"/>
                        </div>
                        <div class="offer-content">
                            <div class="offer-title">
                                <h2>{$offer.title}</h2>
                            </div>
                            <div class="offer-heading">
                                <p>{$offer.description}</p>
                            </div>
                            <div class="offer-author">
                                <p>Posté par: <span class="offer-name"> {$offer.customer_firstname} </span>, le {$offer.date_add|date_format:"%d/%m/%Y"} </p>
                                {if $customer.is_logged && $customer.id == $offer.id_customer}
                                    <a href="{$link->getModuleLink('offers_page2', 'EditOffer', ['id_offer' => $offer.id_offer])}">Modifier mon annonce</a>
                                    <a href="{$link->getModuleLink('offers_page2', 'AllOffers', ['action' => 'delete', 'id_offer' => $offer.id_offer])}" >Supprimer mon annonce</a>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        {else}
            <p>Pas encore d'annonces</p>
        {/if}
    </div>
{/block}