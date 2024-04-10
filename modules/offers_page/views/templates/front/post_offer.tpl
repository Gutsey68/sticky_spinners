{extends file='page.tpl'}
{block name='page_content'}
    <div class="offers_page_post_offer">
        {if isset($confirmation)}
            <p class="alert alert-success">{$confirmation}</p>
        {else}
            <form action="{$link->getModuleLink('offers_page', 'postoffer')}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="offer_title">Titre de l'annonce</label>
                    <input type="text" id="offer_title" name="offer_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="offer_description">Description</label>
                    <textarea id="offer_description" name="offer_description" class="form-control" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="offer_image">Image de l'annonce</label>
                    <input type="file" id="offer_image" name="offer_image" class="form-control-file">
                </div>
                <button type="submit" name="submit_offer" class="btn btn-primary">Publier l'annonce</button>
            </form>
        {/if}
    </div>
{/block}
