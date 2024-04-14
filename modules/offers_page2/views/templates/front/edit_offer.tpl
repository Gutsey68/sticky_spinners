{extends file='page.tpl'}
{block name='page_content'}
<div class="offers_page container">
    <h1>Modifier l'annonce</h1>
    <form action="{$link->getModuleLink('offers_page2', 'EditOffer')}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_offer" value="{$offer->id_offer|escape:'html'}">
        
        <div class="form-group">
            <label for="title">Titre de l'annonce:</label>
            <input type="text" id="title" name="title" value="{$offer->title|escape:'html'}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" required>{$offer->description|escape:'html'}</textarea>
        </div>

        {if $offer->image}
            <div class="form-group">
                <label>Image actuelle:</label>
                <img src="{$offer->image_url}" alt="Image de l'annonce" style="max-width:100px;">
            </div>
        {/if}

        <div class="form-group">
            <label for="image">Changer l'image:</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>

        <button type="submit" name="submit_offer" class="btn btn-primary">Sauvegarder les modifications</button>
    </form>
</div>
{/block}
