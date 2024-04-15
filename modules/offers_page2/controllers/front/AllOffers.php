<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class Offers_page2AllOffersModuleFrontController extends ModuleFrontController {

    public function initContent() {
        
        /**
         * Initialise le contenu du front controller pour afficher toutes les offres valides.
         * Récupère les offres valides à l'aide de la méthode statique getValidOffers de la classe Offers, et prépare les URLs des images associées aux offres.
         * Assignes les offres à Smarty pour le rendu dans le template spécifié.
         */
        parent::initContent();

        $offers = Offers::getValidOffers();

        foreach ($offers as &$offer) {
            if ($offer['image']) {
                $offer['image_url'] = $this->context->link->getBaseLink() . 'modules/' . $this->module->name . '/views/img/' . $offer['image'];
            }
        }

        unset($offer);

        $this->context->smarty->assign(array(
            'offers' => $offers
        ));

        $this->setTemplate('module:offers_page2/views/templates/front/list_offers.tpl');
    }

    /**
     * Traite les actions POST, telles que la suppression d'une offre.
     * Vérifie l'action demandée et exécute l'action correspondante, comme la suppression d'une offre.
     * Redirige ensuite l'utilisateur vers la page des offres après l'exécution de l'action.
     */
    public function postProcess() {
        
        if (Tools::getValue('action')) {

            if (Tools::getValue('action') == 'delete') {

                $id_offer = (int)Tools::getValue('id_offer');
                $offer = new Offers($id_offer);
                $offer->delete(); 

                Tools::redirect($this->context->link->getModuleLink('offers_page2', 'AllOffers'));

            }
        }
    }
}