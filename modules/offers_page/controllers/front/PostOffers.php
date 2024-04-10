<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class OffersPagePostOfferModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    /**
     * Affiche et traite le formulaire de soumission d'annonce.
     */
    public function postProcess()
    {
        if (Tools::isSubmit('submit_offer')) {
            // Traitement des données du formulaire
            $title = Tools::getValue('offer_title');
            $description = Tools::getValue('offer_description');
            
            // Gestion de l'image téléchargée
            if (isset($_FILES['offer_image']) && $_FILES['offer_image']['error'] == 0) {
                // Assurez-vous que le fichier est bien un image et respecte certaines conditions (taille, type, etc.)
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['offer_image']['type'], $allowedMimeTypes)) {
                    // Traitez et enregistrez l'image dans un répertoire de votre choix
                    $path = _PS_MODULE_DIR_.'offers_page/uploads/';
                    $filename = uniqid().'-'.$_FILES['offer_image']['name'];
                    move_uploaded_file($_FILES['offer_image']['tmp_name'], $path.$filename);
                    // Sauvegardez le chemin de l'image avec le reste des informations de l'annonce
                } else {
                    // Gestion des erreurs si le fichier n'est pas une image valide
                }
            }
        }
    }
    

    /**
     * Initialisation du contenu de la page.
     */
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:offers_page/views/templates/front/post_offer.tpl');
    }
}
