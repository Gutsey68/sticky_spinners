<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class Offers_page2PostOffersModuleFrontController extends ModuleFrontController {

    /**
     * Initialise le contenu du contrôleur pour le formulaire de publication d'une offre.
     * Affiche une confirmation si nécessaire et charge les détails d'une offre existante si un identifiant d'offre est fourni.
     * Assignes les données à Smarty pour utilisation dans le template et définit le template de publication d'offre.
     */
    public function initContent() {

        parent::initContent();

        if (Tools::getValue('confirmation')) {

            $this->context->smarty->assign(array(
                'confirmation' => 1
            ));
        }

        if (Tools::getValue('id_offer')) {

            $id_offer = (int)Tools::getValue('id_offer');
            $offer = new Offers($id_offer);

            $this->context->smarty->assign(array(
                'offer' => $offer,
            ));
        }

        $this->setTemplate('module:offers_page2/views/templates/front/post_offer.tpl');
    }

    /**
     * Traite la soumission du formulaire de publication d'une offre.
     * Valide les entrées du formulaire, gère le téléchargement de l'image et crée l'offre dans la base de données.
     * Redirige l'utilisateur vers la liste des offres après la soumission réussie ou affiche des erreurs si des problèmes surviennent.
     */
    public function postProcess() {

        if (Tools::isSubmit('submit_offer')) {

            $title = Tools::getValue('title');
            $description = Tools::getValue('description');
            $image = $_FILES['image'];
    
            $errors = [];
    
            if (empty($title)) {
                $errors[] = $this->module->l('Le titre est obligatoire.');
            }
    
            if (empty($description)) {
                $errors[] = $this->module->l('la description est obligatoire.');
            }
    
            if (isset($image) && $image['error'] === 0) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($image['type'], $allowedMimeTypes)) {
                    $errors[] = $this->module->l('Seul les fichiers JPEG, PNG et GIF sont autorisé.');
                } else {
                    $uploadPath = _PS_MODULE_DIR_ . 'offers_page2/views/img/';
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $imageName = uniqid() . '-' . $image['name'];
                    $destination = $uploadPath . $imageName;
    
                    if (!move_uploaded_file($image['tmp_name'], $destination)) {
                        $errors[] = $this->module->l('Erreur lors de l\'importation.');
                    }
                }
            } else {
                $errors[] = $this->module->l('L\'image est obligatoire');
            }
    
            if (empty($errors)) {
                $offer = new Offers();
                $offer->title = $title;
                $offer->id_customer = $this->context->customer->id;
                $offer->description = $description;
                $offer->image = $imageName;
                $offer->valid = $offer->valid = Configuration::get('OFFERS_PAGE_ENABLE_MODERATION') ? 0 : 1;
                $offer->active      = 1;

                if ($offer->add()) {
                    Tools::redirect($this->context->link->getModuleLink('offers_page2', 'AllOffers'));
                } else {
                    $errors[] = $this->module->l('Failed to save the offer.');
                }
            }
    
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->errors[] = $error;
                }
            }
        }
    }
}