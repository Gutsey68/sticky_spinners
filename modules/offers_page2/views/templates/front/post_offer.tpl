{extends file='page.tpl'}
{block name='page_content'}
<div class="offers_page container">
    <h1>Ajouter une annonce</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="offer_title">Titre de l'annonce:</label>
            <input type="text" name="title" id="offer_title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="offer_description">Description :</label>
            <textarea name="description" id="offer_description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="offer_image">Image:</label>
            <input type="file" name="image" id="offer_image" class="form-control-file">
        </div>
        <div class="form-group">
            <button type="submit" name="submit_offer" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</div>
{/block}