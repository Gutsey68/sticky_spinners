<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class Offers_Page2EditOfferModuleFrontController extends ModuleFrontController {

    /**
     * Constructeur pour le front controller de modification d'une offre.
     */
    public function __construct() {

        parent::__construct();
        $this->context = Context::getContext();
        $this->bootstrap = true;
    }

    /**
     * Initialise le contenu du contrôleur pour l'édition d'une offre.
     * Charge l'offre à partir de l'identifiant fourni via GET, prépare l'URL de l'image si elle existe,
     * et assigne l'offre à Smarty pour utilisation dans le template.
     * Définit également le template à utiliser pour l'édition de l'offre.
     */
    public function initContent() {

        parent::initContent();

        $id_offer = (int)Tools::getValue('id_offer');
        $offer = new Offers($id_offer);

        if ($offer->image) {
            $offer->image_url = $this->context->link->getBaseLink() . 'modules/' . $this->module->name . '/views/img/' . $offer->image;
        }
    
        $this->context->smarty->assign('offer', $offer);
        $this->setTemplate('module:offers_page2/views/templates/front/edit_offer.tpl');
    }
    
    /**
     * Traite la soumission du formulaire d'édition d'offre.
     * Gère la mise à jour de l'offre, y compris le téléchargement et la validation de l'image,
     * ainsi que la mise à jour du titre et de la description de l'offre.
     * Valide également les droits de l'utilisateur pour s'assurer qu'il peut modifier l'offre.
     * Gère les erreurs et redirige vers la liste des offres en cas de succès.
     */
    public function postProcess() {

        if (Tools::isSubmit('submit_offer')) {

            $id_offer = (int)Tools::getValue('id_offer');
            $offer = new Offers($id_offer);
    
            if ($offer->id_customer != $this->context->customer->id) {
                $this->errors[] = $this->module->l('Vous n\'avez pas la permission pour modifier cette annonce.');
                return;
            }
    
            $title = Tools::getValue('title');
            $description = Tools::getValue('description');
            $errors = [];
            
            $image = $_FILES['image'];

            if (isset($image) && $image['error'] === 0) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($image['type'], $allowedMimeTypes)) {
                    $errors[] = $this->module->l('Seul les fichiers JPEG, PNG et GIF sont autorisé.');
                } else {
                    $uploadPath = _PS_MODULE_DIR_ . 'offers_page2/views/img/';
                    $imageName = uniqid() . '-' . $image['name'];
                    $destination = $uploadPath . $imageName;
    
                    if (move_uploaded_file($image['tmp_name'], $destination)) {
                        $offer->image = $imageName;
                    } else {
                        $errors[] = $this->module->l('Erreur lors de l\'importation.');
                    }
                }
            }
    
            if (empty($errors)) {
                $offer->title = $title;
                $offer->description = $description;
                if (!$offer->update()) {
                    $errors[] = $this->module->l('Erreur lors de l\'importation.');
                }
            }
    
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->errors[] = $error;
                }
            } else {
                Tools::redirect($this->context->link->getModuleLink('offers_page2', 'AllOffers'));
            }
        }
    }
}
