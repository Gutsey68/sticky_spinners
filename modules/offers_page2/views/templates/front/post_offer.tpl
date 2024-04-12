{extends file='page.tpl'}
{block name='page_content'}
<div class="offers_page">
    <h1>ajouter une annonce</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="offer_title">titre de l'annonce:</label>
            <input type="text" name="title" id="offer_title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="offer_description">description :</label>
            <textarea name="description" id="offer_description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="offer_image">image:</label>
            <input type="file" name="image" id="offer_image" class="form-control-file">
        </div>
        <div class="form-group">
            <button type="submit" name="submit_offer" class="btn btn-primary">envoyer</button>
        </div>
    </form>
</div>
{/block}
