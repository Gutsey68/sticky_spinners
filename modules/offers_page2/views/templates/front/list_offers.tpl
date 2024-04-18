{extends file='page.tpl'}
{block name='page_content'}
    <div class="offers_page container">
        <div class="row mt-3">
            <div class="col-md-8 ">        
            <h1>Marché des mélomanes</h1>
            <p class="lead">Explorez, achetez, collectionnez</p>
            <p><span class="material-icons">warning</span>Soyez Prudent et Protégez Vos Transactions !</p>
            <p class="mb-3 small"><span class="material-icons">info</span>Avant d'engager une transaction, assurez-vous de l'authenticité des vinyles. Privilégiez des méthodes de paiement sécurisées et ne partagez jamais vos informations personnelles sensibles. Pour les échanges en personne, choisissez toujours un lieu public. En cas de doute sur une annonce, n'hésitez pas à nous la signaler. Votre sécurité est notre priorité !
            </p>
            {if $customer.is_logged}
                <a class="custom-btn" href="{$link->getModuleLink('offers_page2', 'PostOffers')}" >
                    Poster une annonce
                </a>
            {else}        
                <a href="https://www.seyzeriat.fr/vinyl_store/login?back=https%3A%2F%2Fwww.seyzeriat.fr%2Fvinyl_store%2Fmodule%2Foffers_page2%2FAllOffers" class="mt-1">Connectez-vous pour poster une annonce !</a>
            {/if}
            </div>
            <div class="col-md-4">
                <img class="img-fluid" src="https://images.unsplash.com/photo-1596633313465-1256feb1c6d9?q=80&w=2128&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="image de vinyles de collection" />
            </div>
        </div>
        <div class="row mt-3">
            {if $offers|@count > 0}
                <h2 class="ml-1">Dernières annonces</h2> 
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
    </div>
{/block}