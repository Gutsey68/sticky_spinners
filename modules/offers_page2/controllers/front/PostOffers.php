<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class Offers_page2PostOffersModuleFrontController extends ModuleFrontController {

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

    public function postProcess()
    {
        if (Tools::isSubmit('submit_offer')) {
            // Récupérer les données du formulaire
            $title = Tools::getValue('title');
            $description = Tools::getValue('description');
            $image = $_FILES['image'];
    
            // Initialiser un tableau pour stocker les erreurs
            $errors = [];
    
            // Vérifier si tous les champs nécessaires sont remplis
            if (empty($title)) {
                $errors[] = $this->module->l('The title is required.');
            }
    
            if (empty($description)) {
                $errors[] = $this->module->l('The description is required.');
            }
    
            // Gestion du téléchargement de l'image
            if (isset($image) && $image['error'] === 0) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($image['type'], $allowedMimeTypes)) {
                    $errors[] = $this->module->l('Only JPEG, PNG and GIF files are allowed.');
                } else {
                    // Définir le chemin où sauvegarder l'image
                    $uploadPath = _PS_MODULE_DIR_ . 'offers_page2/views/img/';
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $imageName = uniqid() . '-' . $image['name'];
                    $destination = $uploadPath . $imageName;
    
                    // Déplacer l'image téléchargée
                    if (!move_uploaded_file($image['tmp_name'], $destination)) {
                        $errors[] = $this->module->l('Failed to upload image.');
                    }
                }
            } else {
                $errors[] = $this->module->l('An image is required.');
            }
    
            // Si aucune erreur, créer et sauvegarder l'offre
            if (empty($errors)) {
                $offer = new Offers(); // Assurez-vous que la classe Offers est correctement définie
                $offer->title = $title;
                $offer->id_customer = $this->context->customer->id;
                $offer->description = $description;
                $offer->image = $imageName; // Sauvegarder seulement le nom de l'image
                $offer->valid = $offer->valid = Configuration::get('OFFERS_PAGE_ENABLE_MODERATION') ? 0 : 1;
                $offer->active      = 1;

                if ($offer->add()) {
                    Tools::redirect($this->context->link->getModuleLink('offers_page2', 'AllOffers'));
                } else {
                    $errors[] = $this->module->l('Failed to save the offer.');
                }
            }
    
            // S'il y a des erreurs, les afficher
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->errors[] = $error;
                }
            }
        }
    }
    
}
