<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminOffersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'offers'; // Le nom de la table sans le préfixe de la base de données
        $this->className = 'Offer'; // Le nom de la classe de l'objet
        $this->lang = false; // Si votre table gère les langues
        $this->deleted = false; // Si votre table gère la suppression
        $this->explicitSelect = true;

        // Définir les champs à afficher dans la liste
        $this->fields_list = array(
            'id_offer' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'filter_key' => 'a!title',
            ),
            // Ajoutez d'autres champs ici
        );

        parent::__construct();
    }

    // Vous pouvez surcharger des méthodes comme renderList, renderForm, etc., pour personnaliser davantage.
}
