<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class OffersPageAllOffersModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        
        // Récupérer les annonces depuis la base de données
        $offers = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'offers` ORDER BY `date_add` DESC');

        // Passer les annonces au template
        $this->context->smarty->assign('offers', $offers);

        $this->setTemplate('module:offers_page/views/templates/front/all_offers.tpl');
    }
}
