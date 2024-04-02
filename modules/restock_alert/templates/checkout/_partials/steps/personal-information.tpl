{* to do  *} 

{* 
    ->l'utilisateur s'inscrit pour recevoir une notification par e-mail lorsque le produit est de nouveau dispo
    ->back office permettant de voir les alertes actives et gérer les stocks
    -> l'orsqu'un produit est remis en stock, notif automatiquement envoyée aux utilisateurs inscrits pour ce produit 
*}

{* 
    ->bouton alerter lorsque de nouveau en stock avec formulaire pour entrer l'adresse e-mail
    ->créer une table de bdd pour stocker les inscription aux alertes(produit et utilisateur)
         qui vérifiée les stocks et envoie les mails lorsque le stock est mis à jour
*}

{* le hook : displayProductActions *}

<form method="post">
    <fieldset>
        <p class="form-row">
            <label for="alert_email"></label>
            <input type="email" name="ALERT_EMAIL" id="alert_email" value="{$email_restock}" required="required" />
        </p>
        <p class="form-submit">
            <button type="submit" name="submit" class="btn btn-primary">M'inscrire</button>
        </p>
    </fieldset>
</form>