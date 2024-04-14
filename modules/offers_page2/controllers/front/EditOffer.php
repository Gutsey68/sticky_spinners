<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class Offers_Page2EditOfferModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        parent::initContent();
        $id_offer = (int)Tools::getValue('id_offer');
        $offer = new Offers($id_offer);

        if ($offer->image) {
            $offer->image_url = $this->context->link->getBaseLink() . 'modules/' . $this->module->name . '/views/img/' . $offer->image;
        }
    
        $this->context->smarty->assign('offer', $offer);
        $this->setTemplate('module:offers_page2/views/templates/front/edit_offer.tpl');
    }
    

    public function postProcess()
    {
        if (Tools::isSubmit('submit_offer')) {
            $id_offer = (int)Tools::getValue('id_offer');
            $offer = new Offers($id_offer);
    
            if ($offer->id_customer != $this->context->customer->id) {
                $this->errors[] = $this->module->l('You do not have permission to edit this offer.');
                return;
            }
    
            $title = Tools::getValue('title');
            $description = Tools::getValue('description');
            $errors = [];
    
            // Gestion de l'image
            $image = $_FILES['image'];
            if (isset($image) && $image['error'] === 0) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($image['type'], $allowedMimeTypes)) {
                    $errors[] = $this->module->l('Only JPEG, PNG and GIF files are allowed.');
                } else {
                    $uploadPath = _PS_MODULE_DIR_ . 'offers_page2/views/img/';
                    $imageName = uniqid() . '-' . $image['name'];
                    $destination = $uploadPath . $imageName;
    
                    if (move_uploaded_file($image['tmp_name'], $destination)) {
                        $offer->image = $imageName; // Mettez à jour le nom de l'image dans l'objet
                    } else {
                        $errors[] = $this->module->l('Failed to upload image.');
                    }
                }
            }
    
            if (empty($errors)) {
                $offer->title = $title;
                $offer->description = $description;
                if (!$offer->update()) {
                    $errors[] = $this->module->l('Failed to save the offer.');
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
